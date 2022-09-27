<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description: Simple PHP/MS Access/ODBC Attendance Report For FingerTec TA500 Device (FingerTec TCMS v3)

//Configuration
$starting_year = '2021'; // Starting Year in index.php dropdown
$system_dsn ='myattendance'; // System DSN - Data Source Name MS Access Database
$access_dbuser=''; // MS Access Database User. Leave Blank if empty
$access_dbpassword='ingress'; // MS Access Database Password. Leave Blank if empty

//ODBC Connection
$conn=odbc_connect($system_dsn,$access_dbuser,$access_dbpassword);
if (!$conn)
  {exit("Connection Failed: " . $conn);}

//End Configuration

?>