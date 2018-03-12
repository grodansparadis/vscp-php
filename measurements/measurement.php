<!DOCTYPE html>
<?php 
	
	/*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	*/

    include '../../settings.cfg';  // Settings

	// 12 hours
	$ix = new DateInterval('PT12H');

	// Get label
	$label = $_GET["label"];
	trim($label);
    if ( 0 == strlen($label) ) {
		$label = "Measurement";
	}

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
		<title>VSCP measurement</title>
		<style>
			.chart-container {
				width: 90%;
				height: auto;
			}
		</style>

		<!-- Bootstrap core CSS -->
		<link href="js/bootstrap-4.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="measurements.css" rel="stylesheet">

	</head>
	<body>

		This is some text.<br>
		<div class="chart-container">
			<canvas id="mycanvas"></canvas>
		</div>

		<br>

		<div class="container">           
  		<table class="table table-striped">	
		<thead>
			<tr><td class="bg-success" >Data for selected range</td></tr>
		</thead>
		<tbody>	  
		<tr><td><div class="text-success" id="updateTime"></div></td></tr>
		<tr><td><div class="text-success" id="lastReading"></div></td></tr>
		<tr><td><div class="text-success" id="minReading"></div></td></tr>
		<tr><td><div class="text-success" id="maxReading"></div></td></tr>		
		<tr><td><div class="text-success" id="meanReading"></div></td></tr>
		<tr><td><div class="text-success" id="countReading"></div></td></tr>
		</tbody>
		</table>

		<!--	
		<?php echo( $from ); ?>
		<?php echo( " - " ); ?>
		<?php echo( $to ); ?>		
		<?php echo( "<br>"); ?>
		<?php print_r(  $diff ); ?>
		<?php echo( "<br>"); ?>
		<?php print date('d M Y\TH:i:s', strtotime('last day of this month') ); ?>
		-->
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!-- javascript -->
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/moment.min.js"></script>

		<script src="js/bootstrap-4.0.0/assets/js/vendor/popper.min.js"></script>
    	<script src="js/bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>

		<!-- Icons -->		
    	<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    	<script>
      		feather.replace()
		</script>
		
    	<!-- Graphs -->
    	<script src="js/Chart.min.js"></script>

		<!-- Page load handler -->
		<script type="text/javascript">

			$(document).ready(function(){

			var measurement_time = [];
			var measurement_value = [];	

			var data = {
				labels: measurement_time,
				datasets: [{
					label: "<?php echo $label?>",
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

			}

			// Get current measurement reading
			$.ajax({
			    url : "<?php echo $MEASUREMENT_HOST;?>get_current.php",
			    type : "GET",
			    success : function(data) {

				    console.log(data);		

					datetime = data[0].date;
					current_value = data[0].value;		    
				
					$("div#lastReading").text( "Last reading: " + current_value );					
				}
			});

			function truncate (num, places) {
  				return Math.trunc(num * Math.pow(10, places)) / Math.pow(10, places);
			}

			// Get statistics
			$.ajax({
			    url : "<?php echo $MEASUREMENT_HOST;?>get_stats.php",
			    type : "GET",
			    success : function(data) {

				    console.log(data);		

					count = data[0].count;
					max = data[0].max;
					min = data[0].min;
					mean = truncate( data[0].mean, 2 );

					$("div#minReading").text( "Minimum value: " + min );
					$("div#maxReading").text( "Maximum value: " + max );					
					$("div#meanReading").text( "Mean value: " + mean );
					$("div#countReading").text( "# sample points: " + count );					
				}
			});

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

					//absmean = absmean/count;

					data = {
						labels: measurement_time,
						datasets: [{
							label: "<?php echo $label;?>",
							fill: false,
							lineTension: 1,
							backgroundColor: "rgba(59, 89, 152, 0.75 )",
                			borderColor: "rgba(59, 89, 152, 1 )",
                    		pointRadius: 0,  // Don't draw points
							pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
							pointHoverBorderColor: "rgba(59, 89, 152, 1)",						    
							data: measurement_value
						}]
					};
					createChart( data );
					$("div#updateTime").text( "Last updated: " + moment().format("YYYY-MM-DD HH:mm:ss") );
					/*$("div#minReading").text( "Last reading: " + absmin );
					$("div#maxReading").text( "Last reading: " + absmax );
					$("div#meanReading").text( "Last reading: " + absmean );*/
				},
                
                error : function(data) {

                }
            
		    }); }
		
			fetchData();
			setInterval( function() { fetchData(); }, 600000 );
		
		});

		</script>

	</body>
</html>