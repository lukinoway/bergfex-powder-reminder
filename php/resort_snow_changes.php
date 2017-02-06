<?php
$resort = htmlspecialchars($_GET["resort"]);
?>
<html>
<head>
  <script type="text/javascript" src="lib/canvasjs.min.js"></script>
  <script type="text/javascript">
      window.onload = function () {
          var chart = new CanvasJS.Chart("chartContainer", {
		title: {
			text: "<?php echo $resort; ?> Snow Forecast"
		},              
		data: [
              {
                  type: "line",
		  dataPoints: 
<?php

$host = "localhost";
$user = "bergfex";
$pass = "bergfex";
$db = "bergfex";

$con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
	or die ("couldn't connect to server");

$query = "
select date_info, snow
  from weather_entries we1 
 where creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)
   and date_info >= date(now())
   and resort = $1
 order by date_info asc ";

$rs = pg_prepare($con, "resort_snow", $query) or die ("cannot execute query");
$rs = pg_execute($con, "resort_snow", array($resort));

$myarray = array();
while ($row = pg_fetch_row($rs)) {
  $myarray[] = array('label' => $row[0], 'y' => $row[1]);
}
echo json_encode($myarray, JSON_NUMERIC_CHECK);
echo "\n";
pg_close();
?>
}
              ]
          });
 
          chart.render();
      }
  </script>
</head>
 
<body>
  <div id="chartContainer" style="height: 500px; width: 100%;"></div>
</body>
</html>