<?php

$host = "localhost";
$user = "bergfex";
$pass = "bergfex";
$db = "bergfex";

$con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
	or die ("couldn't connect to server");
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
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
    <h1>snow forecast</h1>
  </div>
  
  <div data-role="main" class="ui-content">
    <form>
      <input id="filterTable-input" data-type="search" placeholder="Search For Resort">
    </form>
    <table data-role="table" data-mode="columntoggle" class="ui-responsive ui-shadow" id="myTable" data-filter="true" data-input="#filterTable-input">
<thead align="left" style="display: table-header-group">
<tr>
	<th data-priority="1">Resort</th>
	<th data-priority="2">Region</th>
	<th data-priority="3">Snow</th>
</tr>
</thead>
<tbody>
<?php
$query = "
select resort, region, sum(snow) as total_snow
  from weather_entries we1 
 where creation_dt IN ( select max(creation_dt) from weather_entries we2 where we2.resort = we1.resort and we2.date_info = we1.date_info)
      and date_info >= date(NOW()) 
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
</tbody>
</table>
</div>
  <div data-role="footer">
    <h1>last update 
<?php
$query1 = "select max(creation_dt) as update_dt from weather_entries";
$rs1 = pg_query($con, $query1) or die ("cannot execute query");
while ($row1 = pg_fetch_object($rs1) ) { 
echo $row1->update_dt;
}
?>
    </h1>
  </div>
</div> 
</body>
<?php
pg_close();
?>
</html>
