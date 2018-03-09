<!DOCTYPE html>
<?php 
	/*
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	*/

    include '../../settings.cfg';  // Settings

	// 12 hours
	$ix = new DateInterval('PT12H');

	// get start date
    $dt = new DateTime();
    $from = $_GET["from"];
    trim($from);
    if ( 0 == strlen($from) ) {
		$dtfrom = new DateTime();
		//$dtfrom = $dtfrom->sub($ix);
		//$from = $dtfrom->format('Y-m-d\TH:i:s');
		$from = $dtfrom->format('Y-m-d\T00:00:00');
	}
	

	// Get end date
    $to = $_GET["to"];
    trim($to);
    if ( 0 == strlen($to) ) {
		$dtto = new DateTime();
		//$dtto = $dtto->add($ix);
		//$to = $dtto->format('Y-m-d\TH:i:s');
		$to = $dtto->format('Y-m-d\T23:59:59');
		   
	}
	
	// Get sensor GUID
    $guid = $_GET["guid"];
    trim($guid);
    if ( 0 == strlen($guid) ) {
        $guid = 'FF:FF:FF:FF:FF:FF:FF:FF:61:00:08:01:92:AF:A8:10';    
	}
	
	$d1=new DateTime($from);
    $d2=new DateTime($to);
	$diff=$d2->diff($d1);
	
	$unit = "'minute'";
	if ( $diff->y > 0 ) {
		$unit = "'day'";
	}
    else if ( $diff->d > 0 ) {
		$unit = "'hour'";
	}
	else {
		$unit = "'minute'";
	} 
?>
<html>
	<head>
		<meta http-equiv="refresh" content="1000">
		<meta charset="utf-8">
		<title>Day temperature</title>
		<style>
			.chart-container {
				width: 90%;
				height: auto;
			}
		</style>

	</head>
	<body>

		<div class="chart-container">
			<canvas id="mycanvas"></canvas>
		</div>

		<h6><div id="updateTime">-----</div></h6>

		<!--	
		<?php echo( $from ); ?>
		<?php echo( " - " ); ?>
		<?php echo( $to ); ?>		
		<?php echo( "<br>"); ?>
		<?php print_r(  $diff ); ?>
		<?php echo( "<br>"); ?>
		<?php print date('d M Y\TH:i:s', strtotime('last day of this month') ); ?>
		-->

		<!--
		<button class="btn-load" data-chart="data1">set 1</button>
		<button class="btn-load" data-chart="data2">set 2</button>
		-->
		
		<!-- javascript -->
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/moment.min.js"></script>
		<script type="text/javascript" src="js/Chart.min.js"></script>
		<!-- <script type="text/javascript" src="js/linegraph.js"></script> -->

		<!-- Page load handler -->
		<script type="text/javascript">

			$(document).ready(function(){

			var measurement_time = [];
			var measurement_value = [];	

			var data = {
				labels: measurement_time,
				datasets: [{
					label: "Temperature south wall",
					fill: false,
					lineTension: 1,
					backgroundColor: "rgba(59, 89, 152, 0.75 )",
                	borderColor: "rgba(59, 89, 152, 1)",
                    pointRadius: 0,  // Don't draw points
					pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
					pointHoverBorderColor: "rgba(59, 89, 152, 1)",						    
					data: measurement_value
				}]
			};

			var options = {
				type: 'line',
				animation: false,
				// http://www.chartjs.org/docs/latest/axes/cartesian/time.html#ticks-source
				options: {
					animation: false,
        			scales: {
            			xAxes: [{
							type: 'time',
							//format: "HH:mm",
							unitStepSize: 2,
                			time: {	
								unit: <?php echo $unit; ?>,										
								displayFormats: {
          							'millisecond': 'HH:mm',
            						'second': 'HH:mm',
            						'minute': 'HH:mm',
            						'hour': 'D/M',
            						'day': 'YYYY-MM-DD',
            						'week': 'YYYY-MM-DD',
            						'month': 'YYYY-MM-DD',
            						'quarter': 'YYYY-MM-DD',
									'year': 'YYYY-MM-DD',
          						}
							},
							scaleLabel: {
        						display: true,
        						labelString: '<?php echo( $from . " - " . $to ); ?>'
      						}
						}],
						yAxes: [{
      						scaleLabel: {
        						display: true,
        						labelString: 'degrees Celsius'
      						}
    					}]
                    }
    			}
			};	

			var ctx = $("#mycanvas");
			options.data = data;
			var LineGraph = new Chart( ctx, options );

			function createChart( d )
			{
  				if ( LineGraph) { 
    				LineGraph.destroy();
  				}
				  
			    options.data = d;  
				LineGraph = new Chart( ctx, options );
				/*LineGraph = new Chart( ctx, {
					type: 'line',
					data: d,
					options: options,
				});*/	
			}

			//createChart(data);

			function fetchData(){ $.ajax({
			    url : "<?php echo $MEASUREMENT_HOST;?>get_measurement.php?from=<?php echo $from?>&to=<?php echo $to?>",
			    type : "GET",
			    success : function(data) {

				    console.log(data);		

					measurement_time = [];
					measurement_value = [];		    

				    for ( var i in data ) {
					    measurement_time.push( data[i].date );
					    measurement_value.push( data[i].value );
				    }

					/*LineGraph.config.data = measurement_value;
					var foo = eval($(this).data('chart'));
					console.log(foo);
					createChart(foo); */
					//options.data = data;
					data = {
						labels: measurement_time,
						datasets: [{
							label: "Temperature south wall",
							fill: false,
							lineTension: 1,
							backgroundColor: "rgba(59, 89, 152, 0.75 )",
                			borderColor: "rgba(59, 89, 152, 1)",
                    		pointRadius: 0,  // Don't draw points
							pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
							pointHoverBorderColor: "rgba(59, 89, 152, 1)",						    
							data: measurement_value
						}]
					};
					createChart( data );
					$("div#updateTime").text( "Last updated: " + moment().format("YYYY-MM-DD HH:mm:ss") );		    
				},
                
                error : function(data) {

                }
            
		    }); }
		
			fetchData();
			setInterval( function() { fetchData(); }, 600000 );
		
		});

		$('.btn-load').on('click', function(){
			console.log("--------------");
			/*var foo = eval($(this).data('chart'));
			console.log(foo);*/

			createChart(data);
		});
		</script>

	</body>
</html>