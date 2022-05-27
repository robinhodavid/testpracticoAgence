@extends('layouts.main_template')

@section('title', 'Agence | Performance Comercial | Test de Prueba')

@section('body_content')


	<div class="col my-4"> <!-- Col -->
		<div class="card card-style">
        @if(session('msj'))
	 			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  		<strong>¡Felicidades!</strong> {{ session('msj') }}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
					</button>
				</div>
	 			@endif
	 			@error('consultor')
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
					  <span>
					  	<strong>¡Atención!</strong>Debe seleccionar un valor para el campo Consultor.
					  </span>
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>
				@enderror
        <div class="card-body">
	          <ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
							    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Consultor</button>
							</li>
					  	<li class="nav-item" role="presentation">
					    	<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Cliente</button>
					  	</li>
						</ul>
						<div class="tab-content" id="myTabContent">

					  		<div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
					  			<!-- Tab1 -->	
					  			<form method="GET" action="{{ route('comercial')}}" role="search" class="form-trans">
									@csrf	
					  				<div class="row">
					  					<div class="col my-2">
						  				<table class="table table-bordered">
												<thead>
										    	<tr>
										      	<th style="background: #efefef; width: 136px;">Período</th>
										      	<td>
										      		<div class="form-group">
								                <div class="input-group">
																	<span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar-alt"></i></span>
								                    	<input type="text" class="form-control float-right" id="reservation" name="periodo" value="Seleccione un valor">
								                </div>
								                <!-- /.input group -->
								              </div>
										      	</td>
										    	</tr>
										    	<tr>
										      	<th style="background:#efefef;">Consultores</th>
										      	<td>
											      	<div class="form-group" data-select2-id="40">
										              <select class="select2 select2-hidden-accessible" multiple="" data-placeholder="Seleccione un consultor" style="width: 100%;" data-select2-id="7" tabindex="-1" aria-hidden="true" name="consultor[]">
										              	@foreach($consultorList as $item)
										                <option data-select2-id="{{$item->co_usuario}}" value="{{$item->co_usuario}}">{{$item->no_usuario}}</option>
										                @endforeach
										              </select>
										            </div>
										      	</td>
										    	</tr>
										    	<tr>
										    		<!--Buttom -->
										    		<th colspan="2" style="text-align: right; background: #f8f9fa;">
										    			<button type="submit" class="btn btn-dark" value="1"> <i class="fas fa-fw fa-clipboard text-white"></i>
															Relatório</button>
														</form>		
										    			<button type="button" class="btn btn-dark"><i class="fa fa-solid fa-chart-bar"></i>

															Gráfico</button>

										    			<button id="pizza_btn" type="button" class="btn btn-dark" ><i class="fas fa-fw fa-chart-pie text-white" ></i>
															Pizza</button>
										    		</th>
										    	</tr>
												</thead>								
											</table>
											</div>
					  				</div>			
					  		
					  				<div class="row">
					  					<div class="col my-4">
					  						<div class="card card-style">
					  							@foreach($consultorResult as $key => $value)
					  								<table class="ctable">
					  									<thead>
					  										<tr>
					  												<th colspan="5" class="htitletable rowtable">{{$key}}</th>
					  										</tr>
					  										<tr class="rowtable">
					  												<th class="shtable">Pedíodo</th>
					  												<th class="shtable">Receíta Líquida </th>
					  												<th class="shtable">Custo Fixo</th>
					  												<th class="shtable">Comissao</th>
					  												<th class="shtable">Lucro</th>
					  										</tr>
					  									</thead>
					  									<tbody>
					  										@foreach($value as $item)
					  											<tr>
					  													<td class="btable coltable">{{$item->mes}}</td>
					  													<td class="btable coltable">R$ {{ number_format($item->receita_liquida, 2, ",", ".") }}
					  													</td>
					  													<td class="btable coltable">R$ {{ number_format($item->brut_salario, 2, ",", ".") }}
					  													</td>
					  													<td class="btable coltable">R$ {{ number_format($item->valor_comissao, 2, ",", ".") }}</td>
					  													<td class="btable coltable {!!$item->lucro>0? "text-blue":"text-red" !!}">R$ {{ number_format($item->lucro, 2, ",", ".") }}</td>
					  											</tr>
					  										@endforeach	
					  									</tbody>
					  									<tfoot>
					  										<tr class="rowtable">
					  												<td class="htitletable coltable"><strong>SALDO</strong></td>
					  												
					  												<td class="htable  {!!$value->sum('receita_liquida')>0? "text-blue":"text-red" !!} coltable ">R$ {{ number_format($value->sum('receita_liquida'), 2, ",", ".") }}
					  												</td>
					  												
					  												<td class="htable coltable {!!$value->sum('brut_salario')>0? "text-blue":"text-red" !!}" > R$ {{ number_format($value->sum('brut_salario'), 2, ",", ".") }}
					  												</td>
					  												
					  												<td class="htable coltable {!!$value->sum('valor_comissao')>0? "text-blue":"text-red" !!}"> R$ {{ number_format($value->sum('valor_comissao'), 2, ",", ".") }}
					  												</td>
					  												<td class="htable coltable {!!$value->sum('lucro')>0? "text-blue":"text-red" !!} "> R$ {{ number_format($value->sum('lucro'), 2, ",", ".") }}</td>
					  										</tr>
					  									</tfoot>
					  								</table>
					  							@endforeach
					  						</div>
					  					</div>
					  				</div>

					  				<!--Bar Chart -->

					  				<div class="row">
					  					<div class="col-8 my-4">
					  						<div class="card card-style">
							            <p class="text-center">
							              <strong>Performace Comercial Janeiro de 2019 a Novembro 2019</strong>
							            </p>
							            <div class="chart">
							            	<div class="chartjs-size-monitor">
							            		<div class="chartjs-size-monitor-expand">
							            			<div class=""></div>
							            		</div>
							            		<div class="chartjs-size-monitor-shrink">
							            			<div class=""></div>
							            		</div>
							            	</div>
	            							<!-- Sales Chart Canvas -->
	            							<canvas id="salesChart" height="180" style="height: 180px; display: block; width: 478px;" width="478" class="chartjs-render-monitor"></canvas>
          								</div>
          								<!-- /.chart-responsive -->
        								</div>
        							</div>	
							        <!-- /.col -->
							        <div class="col-md-4">
							          	<p class="text-center">
							            	<strong>Custo Fixo Medio</strong>
							          	</p>
          								<!-- /.progress-group -->
          								<div class="progress-group">
	            							Servicios Realizados
	            							<span class="float-right"><b>#</b>/text</span>
	            							<div class="progress progress-sm">
		              						<div class="progress-bar bg-ser" style="width:80%"></div>
		            						</div>
          								</div>
          								<!-- /.progress-group -->
								          <div class="progress-group">
								            Series en Preñez
								            <span class="float-right"><b>#</b>/Text</span>
								            <div class="progress progress-sm">
								              <div class="progress-bar bg-pre" style="width:70%"></div>
								            </div>
								          </div>
          								<!-- /.progress-group -->
          								<div class="progress-group">
								            <span class="progress-text">Partos Registrados</span>
								            <span class="float-right"><b>#</b>/text</span>
								            <div class="progress progress-sm">
								              <div class="progress-bar bg-par" style="width: 100%"></div>
								            </div>
								          </div>
								          <!-- /.progress-group -->
								          <div class="progress-group">
								            Abortos Registrados
								            <span class="float-right"><b>#</b>/text</span>
								            <div class="progress progress-sm">
								              <div class="progress-bar bg-abo" style="width: 90%"></div>
								            </div>
								          </div>
					  					</div> <!--/.col-->
					  				</div>
					  				<div class="row" >
					  					<div class="col">
					  						<div class="card card-style oculto" id=pierow >
					  							<label style="text-align: center; font-size: 13px; font-weight: 600; margin: 1%;">Participacao na Receita Líquida</label>
							  					<div class="chart-responsive"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
		                      <canvas id="pieChart" style="display: block; width: 230px; height: 430px;" class="chartjs-render-monitor" width="200" height="100"></canvas>
		                    	</div>
	                    	</div>
                    	</div>
                    <!-- ./chart-responsive -->
					  				</div>

					  				<div class="row">
					  					 
					  				</div>

					  		</div> <!-- /end tab-panel-->
					  		

					  		<div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
						  		<!-- Tab2-->
						  			<form method="GET" action="" role="search" class="form-trans">
									@csrf	
					  				<div class="row">
					  					<div class="col my-2">
						  				<table class="table table-bordered">
												<thead>
										    	<tr>
										      	<th style="background: #efefef; width: 136px;">Período</th>
										      	<td>
										      		<div class="form-group">
								                <div class="input-group">
																	<span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar-alt"></i></span>
								                    	<input type="text" class="form-control float-right" id="reservation1" name="tiempo" value="Seleccione un valor">
								                </div>
								                <!-- /.input group -->
								              </div>
										      	</td>
										    	</tr>
										    	<tr>
										      	<th style="background:#efefef;">Cliente</th>
										      	<td>
											      	<div class="form-group" data-select2-id="30">
										              <select class="select2 select2-hidden-accessible" multiple=""  style="width: 100%;" data-select2-id="4" tabindex="-1" aria-hidden="true" name="cliente[]">
										                <option data-select2-id="1" value="">3A Rural  </option>
										                <option data-select2-id="2" value="">Aero Flash</option>
										                <option data-select2-id="4" value="">Agence</option>
										                <option data-select2-id="5" value="">AGENCIA ARROW</option>
										                <option data-select2-id="6" value="">Agricon Consultoria</option>
										              </select>
										            </div>
										      	</td>
										    	</tr>
										    	<tr>
										    		<!--Buttom -->
										    		<th colspan="2" style="text-align: right; background: #f8f9fa;">
										    			<button type="submit" class="btn btn-dark" value="1"> <i class="fas fa-fw fa-clipboard text-white"></i>
															Relatório</button>
										    			<button type="submit" class="btn btn-dark"><i class="fa fa-solid fa-chart-bar"></i>

															Gráfico</button>

										    			<button type="submit" class="btn btn-dark"><i class="fas fa-fw fa-chart-pie text-white"></i>
															Pizza</button>
										    		</th>
										    	</tr>
												</thead>								
											</table>
											</div>
					  				</div>			
					  			</form>
					  		</div> <!-- /end tab-panel-->
					  		
						</div>
      
        </div>           
    </div>
	</div> <!-- /.end-Col-->
	
@endsection

@section('css')
    
    <link rel="stylesheet" href="/css/custom.css">
    
    <!-- daterange picker -->
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
    <!-- Select2 -->
  	<link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
  
@stop

@section('js')

	<!-- Select2 -->
	<script src="/plugins/select2/js/select2.full.min.js"></script>

	<!-- date-range-picker -->
	<script src="/plugins/daterangepicker/daterangepicker.js"></script>

  <script>
  	$(function () {
   
   		//Initialize Select2 Elements
    	$('.select2').select2()

	    //Date range picker
	    $('#reservation').daterangepicker({
	    	startDate:'01/01/2007',
	    	endDate: '31/01/2007',
	    	locale: { format: "DD/MM/YYYY" }	
	    });
 
	    //Date range picker with time picker
	    $('#reservationtime').daterangepicker({
	      timePicker: true,
	      timePickerIncrement: 30,
	      locale: {
	        format: 'MM/DD/YYYY hh:mm A'
	      }
	    });
  	});
  </script>

	<script>
	
		var chartPluginLineaHorizontal = {
  	afterDraw: function(chartobj) {
    if (chartobj.options.lines) {
      var ctx = chartobj.chart.ctx;
      for (var idx = 0; idx < chartobj.options.lines.length; idx++) {
        var line = chartobj.options.lines[idx];
        line.iniCoord = [0,0];
        line.endCoord = [0,0];
        line.color = line.color ? line.color : "red";
        line.label = line.label ? line.label : "";
        if (line.type == "horizontal" && line.y) {
          line.iniCoord[1] = line.endCoord[1] = chartobj.scales["y-axis-0"].getPixelForValue(line.y);
          line.endCoord[0] = chartobj.chart.width;
        } else if (line.type == "vertical" && line.x) {
          line.iniCoord[0] = line.endCoord[0] = chartobj.scales["x-axis-0"].getPixelForValue(line.x);
          line.endCoord[1] = chartobj.chart.height;
        }
        ctx.beginPath();
        ctx.moveTo(line.iniCoord[0], line.iniCoord[1]);
        ctx.lineTo(line.endCoord[0], line.endCoord[1]);
        ctx.strokeStyle = line.color;
        ctx.stroke();
        ctx.fillStyle = line.color;
        ctx.fillText(line.label, line.iniCoord[0] + 3, line.iniCoord[1] + 3);
      }
    }
  }
}
Chart.pluginService.register(chartPluginLineaHorizontal);

var cant      = <?php echo json_encode($cant); ?>;
var date      = <?php echo json_encode($date); ?>;
var custofixo = <?php echo json_encode($custoMedio); ?>;

var ctx = document.getElementById('salesChart').getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: date,//[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, ],
    datasets: [

    {
      label: 'Consultor A',
      data: [86984, '4', '1', '3', '1', '0', '0', '1', '11', '4', '8', '7', '5', '1', '2', '5', '3', '8', '1', ],
      backgroundColor: "rgba(0,255,51,0.5)"
    }, 
    {
      label: 'Consultor B',
      data: [358620, 4, 5, 8, 9, 9, 9, 10, 21, 25, 33, 40, 45, 50, 51, 53, 58, 61, 69, 73],
      backgroundColor: "rgba(0,153,255,0.5)"
    }
    ]
  },
  options: {
    lines: [
      {
        type: "horizontal",
        y: custofixo,
        color: "red",
        label: "Custo Fixo"
      },

    ]
  }
});
	</script>

<script>

    var conuslt = <?php echo json_encode($noUsuario); ?>;
    var percent = <?php echo json_encode($porcentaje); ?>;

    var pie = {
    labels: conuslt,  
    datasets: [
        {
          label: 'Participaco na Receita Líquida',
          data: percent,
          backgroundColor : ['#63b8b8', '#af9cd6', '#e36c5f', '#99a3a4','#33b8b8', 
          									 '#af9cd8', '#e36a5f', '#99b3b4','#e16b5f','#b26a5f','#eeaaee','#4abbb8'],
        }
      ]
    }
    
    //-------------
    //- PIE CHART -
    //-------------
    
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = pie;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {

      type: 'pie',
      label: 'Participaco na Receita Líquida',
      data: pieData,
      options: pieOptions
    
    })	
</script>
<script>

	let pizza_btn = document.getElementById("pizza_btn");

	pizza_btn.addEventListener("click",function (){
		//document.getElementById("pierow").style.display = 'visible';		
		$("#pierow").removeClass("oculto");
	});


/*
	$(document).ready(function(){

		$("#piz_btn").click(function(){
			alert('Click en el boton');	
			$("#pierow").addClass("rojo grande26");
		});
	/*
		$("#boton02").click(function(){
			$("#parrafo").addClass("rojo grande26");
		});
		$("#boton03").click(function(){
			$("#parrafo").removeClass("rojo");
		});
		$("#boton04").click(function(){
			$("#parrafo").removeClass("rojo grande26");
		});
		*//*

});*/
</script>


@stop