<?php

$host = "localhost";
$user = "bergfex";
$pass = "bergfex";
$db = "bergfex";

$con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
	or die ("couldn't connect to server");
?>
<?php
$query = "
select resort, region, sum(snow) as total_snow
  from weather_entries we1 
 where creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)
      and date_info between date(NOW()) and date(now())+3 
group by resort, region
 order by total_snow desc";

$rs = pg_query($con, $query) or die ("cannot execute query");

while ($row = pg_fetch_object($rs) ) { 
?>
<tr class="item_row">	
	<td><a href="resort_snow_changes.php?resort=<?php echo $row->resort; ?>" target="_blank"><?php echo $row->resort; ?></a></td>
	<td><?php echo $row->region; ?></td>
	<td><?php echo $row->total_snow; ?></td>
</tr>	
<?php 
}
?>
<?php
$query1 = "select max(creation_dt) as update_dt from weather_entries";
$rs1 = pg_query($con, $query1) or die ("cannot execute query");
while ($row1 = pg_fetch_object($rs1) ) { 
echo $row1->update_dt;
}
?>
<?php
pg_close();
?>