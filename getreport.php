<?php
include "config.php";
?>
<script>
window.history.forward(1);
</script>
<?php
$selectedmonth = intval($_POST['selectedmonth']);
$selectedyear = intval($_POST['selectedyear']);
$selecteduser = $_POST['selecteduser'];

$nextmonth1=Date("m", strtotime("" . $selectedyear . "-" . $selectedmonth . "-01" . " +1 month"));
if ($nextmonth1=='01')
	{
	$nextmonth=12;
	}
else
	{
	$nextmonth=Date("m", strtotime("" . $selectedyear . "-" . $selectedmonth . "-01" . " +1 month"));
	}
  
$month = "$selectedmonth";
$year = "$selectedyear";
$monthNum = $month;
$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));

$currentdate=date("Y-m-d");

if ($selectedmonth=='1')
{$mymonth='01';}
else if ($selectedmonth=='2')
{$mymonth='02';}
else if ($selectedmonth=='3')
{$mymonth='03';}
else if ($selectedmonth=='4')
{$mymonth='04';}
else if ($selectedmonth=='5')
{$mymonth='05';}
else if ($selectedmonth=='6')
{$mymonth='06';}
else if ($selectedmonth=='7')
{$mymonth='07';}
else if ($selectedmonth=='8')
{$mymonth='08';}
else if ($selectedmonth=='9')
{$mymonth='09';}
else if ($selectedmonth=='10')
{$mymonth='10';}
else if ($selectedmonth=='11')
{$mymonth='11';}
else if($selectedmonth=='12')
{$mymonth='12';}
else{}	

$selecteddate= "$selectedyear-$mymonth-01";

if ($selecteddate > $currentdate)
{
echo "There is no data yet for the selected month";
}
else{  
	
$monthName = date("F", mktime(0, 0, 0, $selectedmonth, 10));
echo "<div class='container'>";
echo "<span style='text-transform:uppercase'><b>Staff Attendance Report</b></span><br>";

// User Info Query
$userquery="SELECT user.userid,user.Name,user.User_Group,user.ic,user_group.id,user_group.gName,user_group.parentId FROM user AS user INNER JOIN user_group AS user_group ON user.User_Group=user_group.id WHERE user.ic='$selecteduser'";

$rs=odbc_exec($conn,$userquery);
if (!$rs)
  {exit("Error in SQL");}

while (odbc_fetch_row($rs))
{
  $userid=odbc_result($rs,"userid");
  $name=odbc_result($rs,"Name");
  $userdeptid=odbc_result($rs,"User_Group");
  $icnumber=odbc_result($rs,"ic");
  $deptname=odbc_result($rs,"gName");


echo "<b>Name:</b> $name &nbsp;&nbsp;<b>IC Number:</b> $icnumber &nbsp;&nbsp;<b>Department:</b> $deptname ";

}
echo "<br /><b>Month:</b> $monthName $selectedyear</center><br />";
echo "</div>";

$month = "$selectedmonth";
$year = "$selectedyear";
$start_date = "01-".$month."-".$year;
$start_time = strtotime($start_date);
$end_time = strtotime("+1 month", $start_time);

echo "<div class='container'>";
echo "<table class='table table-hover table-bordered table-striped'>";
echo "<thead><tr>";
echo "<th width='150'><b>Date</b></th>";
echo "<th><b>Attendance Log </b></th>";

echo "</tr></thead><tbody>";

//start for loop
for($i=$start_time; $i<=$end_time; $i+=86400)
{
$monthlydate = date('d M Y D', $i);
echo "<tr><td>";
echo $monthlydate;
echo "</td>";

// Check Time Query
$checktimequery="SELECT userid,checktime FROM auditdata WHERE userid='$userid' AND YEAR(checktime)='$selectedyear' ORDER BY checktime ASC";
$rs2=odbc_exec($conn,$checktimequery);
if (!$rs2)
  {exit("Error in SQL");} 

echo "<td>";
 while (odbc_fetch_row($rs2))
{ 

$monthlydate2=date('j.n.Y',$i);
$checktime=odbc_result($rs2,"checktime");
$checktime_format = date('j.n.Y', strtotime($checktime)); 
$time = date('g:ia', strtotime($checktime)); 

if ($monthlydate2==$checktime_format)
{
  echo "$time&nbsp;&nbsp;";
}

}
echo "</td>";
echo "</tr>";

} // end for Loop 

echo "</tbody></table>";

} 


?>