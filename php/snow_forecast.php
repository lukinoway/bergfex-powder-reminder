<?php
include 'list_data.php';

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
	<th data-priority="6">Region</th>
	<th data-priority="1">Snow [cm]</th>
</tr>
</thead>
<tbody>
<?php
	$rs = get_snow_list($from, $to);
	while ($row = pg_fetch_object($rs) ) {
?>
<tr class="item_row">
	<td><a href="resort_info.php?resort=<?php echo $row->resort; ?>" target="_blank"><?php echo $row->resort; ?></a></td>
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
    <h1>last update <?php echo get_weather_changedate(); ?>
    </h1>
  </div>
</div>
</body>
</html>
