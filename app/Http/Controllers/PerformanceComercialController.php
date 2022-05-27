<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; 
use Illuminate\Support\Arr;

class PerformanceComercialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      
        # para que Objeto $request->periodo no entre en null
        if (!is_null($request->periodo)) {
            # Obtenemos el rango de fecha.
            $dateRange = explode('-', $request->periodo);
            # Eliminamos los espacio en el string 
            $dateRange = str_replace(" ","",$dateRange); 

            # Por condiciones de almacenaje en la base de datos cambiamos el formato internamente
            # para ellos usamos Carbon como herramienta para el manejador de fechas.
        
            $dateRange [0] = Carbon::createFromFormat('d/m/Y', $dateRange [0])->format('Y-m-d');
            $dateRange [1] = Carbon::createFromFormat('d/m/Y', $dateRange [1])->format('Y-m-d');
        }
        

        # 1.- Listado de Consultores
        $consultorList = DB::table('cao_usuario')
                  ->join('permissao_sistema','permissao_sistema.co_usuario','=','cao_usuario.co_usuario')
                  ->where([
                          ['permissao_sistema.co_sistema','=','1'],
                          ['permissao_sistema.in_ativo','=','S'],
                          ])
                  ->whereIn('permissao_sistema.co_tipo_usuario',['0','1','2'])
                  ->get();
      
        # 2.- Utilizaremos Query Builder para mostrar los resultados; creando un solo bloque consulta

        # 2.1 Sumatoria de Todas las facturas de órdenes de servicios          
        $valor      = 'ROUND(SUM(cao_fatura.valor),2)';          
        
        # 2.2 Calculo de Total Impuesto Inc lo denominaremos  $impuestoR 
        $impuestoR    = 'ROUND(SUM((cao_fatura.valor*(cao_fatura.total_imp_inc/100))))';

        # Recita liquida que se aquiere con la diferencia entre el valor por mes y los impuestos generados en 
        # mismo.
        $receita_liquida    = '('.$valor.'-'.$impuestoR.')';
        
        # Comisiones generadas por cconsultor 
        $valor_comissao     = ' ROUND(SUM(((cao_fatura.valor - (cao_fatura.valor*(cao_fatura.total_imp_inc/100)) )*(cao_fatura.comissao_cn/100) )),2)  ';
        
        $custo_fixo = 'cao_salario.brut_salario';

        $lucro = 'ROUND(('.$receita_liquida.') - ('.$custo_fixo.' + '.$valor_comissao.'),2)';

        # ->groupBy('mes','cao_usuario.co_usuario','total_imp_inc','receita_liquida','valor_comissao','cao_salario.brut_salario')

        # Formato de Fecha.
        setlocale(LC_TIME, 'pt_BR');

        # Formato de Monedas
        setlocale(LC_MONETARY, 'es_BRL');
        
        $query = DB::table('cao_usuario')
            ->select('cao_usuario.no_usuario','cao_usuario.co_usuario','cao_salario.brut_salario',
                DB::raw('DATE_FORMAT(cao_fatura.data_emissao, "%b-%Y") as mes'),
                DB::raw(''.$valor.'as valor'), 
                DB::raw(''.$impuestoR.' as total_imp_inc'),
                DB::raw(''.$receita_liquida.' as  receita_liquida'),
                DB::raw(''. $valor_comissao.' as  valor_comissao'),
                DB::raw(''. $lucro.' as  lucro')
                )
            ->groupBy('mes','cao_usuario.co_usuario','cao_salario.brut_salario')

            ->join('cao_os','cao_os.co_usuario','=','cao_usuario.co_usuario')
            ->join('cao_fatura','cao_fatura.co_os','=','cao_os.co_os')
            ->join('cao_salario','cao_salario.co_usuario','=','cao_usuario.co_usuario')
            
            ->orderBy('cao_usuario.co_usuario','ASC');

            ($request->consultor == null) ? "": $query->whereIn('cao_os.co_usuario',$request->consultor);
            ($request->periodo   == null) ? "": $query->whereBetween('cao_fatura.data_emissao',[$dateRange [0],$dateRange [1]]);
   
        $consultorResult = $query->get()->groupBy('no_usuario');           

        # Hacemo una nueva consulta unicamente a la tabla cao_salario, para asi obtener el promedio costoMedio 
        $query1 = DB::table('cao_salario')
            ->orderBy('co_usuario','ASC');
            ($request->consultor == null) ? "": $query->whereIn('co_usuario',$request->consultor);
        $custoProm  = $query1->get()->avg('brut_salario');

        # Custo medio de los consultores 
        $custoMedio [] = round($custoProm,2);

        # Cramos un collapse de la consulta para menejarlo en un solo array de registros y presentar las graficas.
        $consResult = $consultorResult->collapse();

        $consultaCollectMes = $consResult->groupBy('mes');


        # Recorremos la colección para obtener los meses.
        foreach ($consultaCollectMes as $key => $value) {
           $date []      = $key; 
           foreach ($value as $item) {
               $cant [$key] = $value->count();
               $registros []= $item->no_usuario;
           }
        }
       
        # consultores agrupados
        $consultaCollectConsult  = $consResult->groupBy('no_usuario');

        # Obtenemos en un array los consultores
        foreach ($consultaCollectConsult as $key => $value) {
            $noUsuario [] = $key;
        }
        # la receita
        $receitaL = $consResult->whereIn('mes',$date)->whereIn('no_usuario',$noUsuario)->pluck('receita_liquida');

    
        

       # Obtenemos los datos para la Diagrama de Pizza. 
       # Sumatoria total de receita
        $totalReceita = $consResult->sum('receita_liquida');

        # Calculamos en los procentaje individuales
        foreach ($consultorResult as $key => $value) {
            
            foreach ($value as $item) {
                $percent [] = round(($value->sum('receita_liquida')*100)/$totalReceita,2);
            }
        }

        # Obtenemos los porcentajes de cada consultor
        $porcentaje = array_unique($percent);                        
        # Ordenamos el array
        $porcentaje = array_values($porcentaje);

        

        return view('performance_comercial',get_defined_vars()); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
