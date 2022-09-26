<?php

//Configuration
$starting_year = '2021'; // Starting Year in index.php dropdown
$system_dsn ='mykehadiran'; // System DSN - Data Source Name MS Access Database
$access_dbuser=''; // MS Access Database User. Leave Blank if empty
$access_dbpassword='ingress'; // MS Access Database Password

//ODBC Connection
$conn=odbc_connect($system_dsn,$access_dbuser,$access_dbpassword);
if (!$conn)
  {exit("Connection Failed: " . $conn);}

//End Configuration

?>