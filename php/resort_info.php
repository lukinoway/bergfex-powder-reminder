<?php
include 'chart_data.php';

$resort = htmlspecialchars($_GET["resort"]);

// default values (today and today + 10days)
$from = date('d-m-Y');
$to = date('d-m-Y', mktime(0, 0, 0, date("m")  , date("d")+10, date("Y")));


// check if parameters are set
if (isset($_GET['from'])) {
    $from = htmlspecialchars($_GET["from"]);
}
if (isset($_GET['to'])) {
    $to = htmlspecialchars($_GET["to"]);
}
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="lib/canvasjs.min.js"></script>
<script type="text/javascript">
    $(document).on("pageshow","#pageone",function(){ // When entering pagetwo
            //alert("pageone is now shown");
            var snowForecast = new CanvasJS.Chart("snowForecastChart", {
              data: [ <?php echo get_snow_forecast($resort, $from, $to); ?> ]
            });
            snowForecast.render();
            var sunForecast = new CanvasJS.Chart("sunForecastChart", {
              data: [ <?php echo get_sun_forecast($resort, $from, $to); ?> ]
            });
            sunForecast.render();
            var tempForecast = new CanvasJS.Chart("tempForecastChart", {
              data: [ <?php echo get_temp_forecast($resort, $from, $to); ?> ]
            });
            tempForecast.render();
    });


    $(document).on("pageshow","#pagetwo",function(){ // When entering pagetwo
            //alert("pagetwo is now shown");
            var snowHeight = new CanvasJS.Chart("snowHeightChart", {
              data: [ <?php echo get_snow_height($resort, $from, $to); ?> ]
            });
            snowHeight.render();
    });
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
    <h1><?php echo $resort; ?> weather forecast</h1>
  </div>

  <div data-role="main" class="ui-content">
	  <div id="snowForecastChart" style="height: 400px; width: 100%;"></div>
      <div id="sunForecastChart" style="height: 400px; width: 100%;"></div>
      <div id="tempForecastChart" style="height: 400px; width: 100%;"></div>

  </div>
  <div data-role="footer">
	  <a href="#pagetwo">Check Snow Heights</a>
	  <a href="http://www.bergfex.at/<?php echo $resort; ?>" target="_blank">Bergfex Info</a>
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
	  <a href="http://www.bergfex.at/<?php echo $resort; ?>" target="_blank">Bergfex Info</a>
  </div>
</div>
</body>
</html>
