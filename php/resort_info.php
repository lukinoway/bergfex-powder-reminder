<?php
$resort = htmlspecialchars($_GET["resort"]);
include 'chart_data.php';
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="lib/canvasjs.min.js"></script>
<script type="text/javascript">
	window.onload = function () {
		var snowHeight = new CanvasJS.Chart("snowHeightChart", {
		  data: [ <?php echo get_snow_height($resort); ?> ]
	  	});
		snowHeight.render();
		var snowForecast = new CanvasJS.Chart("snowForecastChart", {
		  data: [ <?php echo get_snow_forecast($resort); ?> ]
	  	});
		snowForecast.render();
	}
</script>
<style>
th {
    border-bottom: 1px solid #d6d6d6;
}

tr:nth-child(even) {
    background: #e9e9e9;
}
</style>
</head>
<body>

<div data-role="page" id="pageone">
  <div data-role="header">
    <h1><?php echo $resort; ?> snow forecast</h1>
  </div>

  <div data-role="main" class="ui-content">
	  <div id="snowForecastChart" style="height: 400px; width: 100%;"></div>
  </div>
  <div data-role="footer">
	  <a href="#pagetwo">Check Snow Heights</a>
  </div>
</div>

<div data-role="page" id="pagetwo">
  <div data-role="header">
    <h1><?php echo $resort; ?> snow heights</h1>
  </div>

  <div data-role="main" class="ui-content">
	  <div id="snowHeightChart" style="height: 400px; width: 100%;"></div>
  </div>
  <div data-role="footer">
	  <a href="#pageone">Check Snow Forecast</a>
  </div>
</div>
</body>
</html>
