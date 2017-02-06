<?php

$host = "localhost";
$user = "bergfex";
$pass = "bergfex";
$db = "bergfex";

$con = pg_connect ("host=$host dbname=$db user=$user password=$pass")
	or die ("couldn't connect to server");

$query = "SELECT VERSION()";
$rs = pg_query($con, $query) or die ("cannot execute query");
$row = pg_fetch_row($rs);

echo $row[0] . "\n";

pg_close();

?>