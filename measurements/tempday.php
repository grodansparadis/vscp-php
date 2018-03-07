<!DOCTYPE html>
<?php 
    include 'settings.cfg';  // Settings

    $dt = new DateTime();
    $from = htmlspecialchars( $_GET["from"] );
    trim($from);
    if ( 0 == strlen($from) ) {
        $from = $dt->format('Y-m-d\T00:00:00');
    }

    $to = htmlspecialchars( $_GET["to"] );
    trim($to);
    if ( 0 == strlen($to) ) {
        $to = $dt->format('Y-m-d\T23:59:59');    
    }
?>
<html>
	<head>
        <meta http-equiv="refresh" content="60">
		<title>Day temperature</title>
		<style>
			.chart-container {
				width: 70%;
				height: auto;
			}
		</style>

	</head>
	<body onload="loadPage()">

		<div class="chart-container">
			<canvas id="mycanvas"></canvas>
		</div>
		
		<!-- javascript -->
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/moment.min.js"></script>
		<script type="text/javascript" src="js/Chart.min.js"></script>
		<!-- <script type="text/javascript" src="js/linegraph.js"></script> -->

		<!-- Page load handler -->
		<script>
			function loadPage() {
				
			}
		</script>

		<script type="text/javascript">

			$(document).ready(function(){
		
			$.ajax({
			    url : "<?php echo $MEASUREMENT_HOST;?>get_measurement.php?from=<?php echo $from?>&to=<?php echo $to?>",
			    type : "GET",
			    success : function( data ) {

				    console.log(data);

				    var measurement_time = [];
				    var measurement_value = [];

				    for ( var i in data ) {
					    measurement_time.push( data[i].date );
					    measurement_value.push( data[i].value );
				    }

				    var config = {
					    type: 'line',
					    data: {
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
					    },
					    // http://www.chartjs.org/docs/latest/axes/cartesian/time.html#ticks-source
					    options: {
        				    scales: {
            				    xAxes: [{
								    type: 'time',
								    format: "HH:mm",
								    unit: 'hour',
								    unitStepSize: 2,
                				    time: {											
									    displayFormats: {
          								    'millisecond': 'HH:mm',
            							    'second': 'HH:mm',
            							    'minute': 'HH:mm',
            							    'hour': 'HH:mm',
            							    'day': 'HH:mm',
            							    'week': 'HH:mm',
            							    'month': 'HH:mm',
            							    'quarter': 'HH:mm',
										    'year': 'HH:mm',
										    min: '10:00',
      									    max: '23:59'
          							    }
								    },
								    scaleLabel: {
        							    display: true,
        							    labelString: 'hour'
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
				    var LineGraph = new Chart(ctx, config );
			    },
                
                error : function(data) {

                }
            
		    });
		
		});
		</script>

	</body>
</html>