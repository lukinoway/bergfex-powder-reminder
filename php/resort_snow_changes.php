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
                  type: "splineArea",
		  dataPoints: 
<?php

$host = "localhost";
$user = "bergfex";
$pass = "bergfex";
$db = "bergfex";

$con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
	or die ("couldn't connect to server");

$query = "
with resort_date as (
        select resort, region, date_info, max(id) as id 
          from weather_entries 
         where date_info >= date(now())
           and resort = $1 
         group by resort, region, date_info
)
select rd.date_info, we.snow 
  from weather_entries we
  inner join resort_date rd on rd.id = we.id
  order by rd.date_info asc";

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
  <div id="chartContainer" style="height: 100%; width: 100%;"></div>
</body>
</html>
