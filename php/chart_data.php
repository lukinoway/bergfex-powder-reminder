<<?php
$host = "localhost";
$user = "bergfex";
$pass = "bergfex";
$db = "bergfex";

function get_snow_forecast($resort, $from, $to) {
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
             where date_info between date($1) and date($2)
               and resort = $3
             group by resort, region, date_info
    )
    select rd.date_info, we.snow
      from weather_entries we
      inner join resort_date rd on rd.id = we.id
      order by rd.date_info asc";

    $rs = pg_prepare($con, "resort_snow", $query) or die ("cannot execute query");
    $rs = pg_execute($con, "resort_snow", array($from, $to, $resort));

    $myarray = array();
    while ($row = pg_fetch_row($rs)) {
      $myarray[] = array('label' => $row[0], 'y' => $row[1]);
    }
    pg_close();

    $chartdata = "";
    $chartdata .= buildChartData("splineArea", true, "snow_forecast", "snow forecast", $myarray);
    return $chartdata;
}

function get_snow_height($resort, $from, $to) {
    $host = "localhost";
    $user = "bergfex";
    $pass = "bergfex";
    $db = "bergfex";

    $con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
    	or die ("couldn't connect to server");

    $query = "
    with snow_date as (
    	select resort, region, date_info, max(id) as id
    	  from snow_entries
    	 where resort = $1
           and date_info between date($2) and date($3)
    	 group by resort, region, date_info
    )
    select sd.date_info, se.snow_berg, se.snow_tal,
           se.snow_berg_change, se.snow_tal_change
      from snow_entries se
      join snow_date sd on sd.id = se.id
     order by sd.date_info";

    $rs = pg_prepare($con, "resort_snow_height", $query) or die ("cannot execute query");
    $rs = pg_execute($con, "resort_snow_height", array($resort, $from, $to));

    $myarray_berg = array();
    $myarray_tal = array();
    $myarray_berg_change = array();
    $myarray_tal_change = array();
    while ($row = pg_fetch_row($rs)) {
      $myarray_berg[] = array('label' => $row[0], 'y' => $row[1]);
      $myarray_tal[] = array('label' => $row[0], 'y' => $row[2]);
      $myarray_berg_change[] = array('label' => $row[0], 'y' => $row[3]);
      $myarray_tal_change[] = array('label' => $row[0], 'y' => $row[4]);
  }
    pg_close();

    $chartdata = "";
    $chartdata .= buildChartData("splineArea", true, "snow_berg", "snow height berg", $myarray_berg) . ",";
    $chartdata .= buildChartData("splineArea", true, "snow_tal", "snow height tal", $myarray_tal) . ",";
    $chartdata .= buildChartData("column", true, "snow_berg_change", "snow change berg", $myarray_berg_change) . ",";
    $chartdata .= buildChartData("column", true, "snow_tal_change", "snow change tal", $myarray_tal_change);

    return $chartdata;
}

function buildChartData($type, $showInLegend, $seriesName, $legendText, $data) {
    $output = "{ type: \"" . $type . "\", showInLegend: " . $showInLegend . ", name: \"" . $seriesName . "\", legendText: \"" . $legendText . "\", dataPoints: " . json_encode($data, JSON_NUMERIC_CHECK) . "}";
    return $output;
}
?>
