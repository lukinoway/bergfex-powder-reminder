<?php

function get_snow_list($from, $to) {
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
    	   --and resort like 'kitzsteinhorn-kaprun'
    	 group by resort, region, date_info
     )
     select rd.resort, rd.region, sum(w1.snow) as total_snow
       from weather_entries w1
       inner join resort_date rd on rd.id = w1.id
      group by rd.resort, rd.region
      order by total_snow desc;
    ";

    $rs = pg_prepare($con, "forecast_list", $query) or die ("cannot execute query");
    $rs = pg_execute($con, "forecast_list", array($from, $to));

    // $myarray = array();
    // while ($row = pg_fetch_row($rs)) {
    //   $myarray[] = array('label' => $row[0], 'y' => $row[1]);
    // }
    pg_close();

    return $rs;
}

function get_weather_changedate() {
    $host = "localhost";
    $user = "bergfex";
    $pass = "bergfex";
    $db = "bergfex";

    $con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
        or die ("couldn't connect to server");

    $query1 = "select max(creation_dt) as update_dt from weather_entries";
    $rs = pg_query($con, $query1) or die ("cannot execute query");

    pg_close();

    $output = "";
    while ($row1 = pg_fetch_object($rs) ) {
        $output .= $row1->update_dt;
    }

    return $output;
}

 ?>
