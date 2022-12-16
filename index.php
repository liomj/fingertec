<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description:  PHP/MS Access/ODBC Attendance Report For FingerTec TA500 Device (FingerTec TCMS v3)

include "config.php";
echo "<html><head>";
echo "<title>Staff Attendance Report</title>";
echo "<meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
echo "<link rel='stylesheet' href='assets/css/bootstrap.min.css'>";
echo "<script src='assets/js/jquery.min.js'></script>";
echo "<link href='assets/css/select2-bootstrap.css' rel='stylesheet' />";
echo "<link href='assets/css/select2.min.css' rel='stylesheet' />";
echo "<script src='assets/js/select2.min.js'></script>";
echo "<link rel='stylesheet' type='text/css' href='assets/css/datatables.min.css'/>
<script type='text/javascript' src='assets/js/datatables.min.js'></script>";
echo "<link href='assets/css/navbar-top-fixed.css' rel='stylesheet'>";
?>

<style>.select2-container .select2-selection { height: 38px; overflow: auto; } </style>

<script type="text/javascript">
$(document).ready(function() {
  $(".myuser").select2({
    theme: "bootstrap"
});
});

$(document).ready(function() {
  $(".myyear").select2({
    theme: "bootstrap"
});
});

$(document).ready(function() {
  $(".mymonth").select2({
    theme: "bootstrap"
});
});
</script>

<script>
function showReport() {

	var str=document.getElementById("myyear").value;
    var str1=document.getElementById("mymonth").value;
	var str2=document.getElementById("myuser").value;

  if(str == '' || str1 == '' || str2 == ''){
	  //  if(str == '' || str1 == ''){
    document.getElementById("txtHint").innerHTML="";
    return;
  } 
                if (window.XMLHttpRequest) {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else {
                    // code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
               
        xmlhttp.onreadystatechange=function() {
                    if (this.readyState==4 && this.status==200) {
                        document.getElementById("txtHint").innerHTML=this.responseText;
                    }
					else 
					 {
						document.getElementById("txtHint").innerHTML="<center><img src='assets/images/ajax-loader.gif' border='0' alt='running' /><br />Please wait</center>";
                       
                    }
                }

               
               xmlhttp.open("POST","getreport.php",true);
			   xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("selectedyear="+str+"&selectedmonth="+str1+"&selecteduser="+str2);
			  
            }

</script>

<?php
echo "</head><body>";
include "menu.php";

echo "<main class='container'>";
echo "<div class='p-5'>";
echo "<br><span style='text-transform:uppercase'><b>Staff Attendance Report (FingerTec TA500 Device - FingerTec TCMS v3)</b></span><br><br>";


echo "<div class='alert alert-info'>";

 echo "<div class='form-group'>";
    echo "<label for='myuser'> Name :</label>";
echo "<select name='myuser' id='myuser' onchange='showReport()' class='myuser form-control'>
      <option value=''>Select Name</option>";
	  
$userquery="SELECT user.userid,user.Name,user.lastname,user.User_Group,user.ic,user_group.id,user_group.gName,user_group.parentId FROM user AS user 
INNER JOIN user_group AS user_group 
ON user.User_Group=user_group.id";
	  
$rs=odbc_exec($conn,$userquery);
if (!$rs)
  {exit("Error in SQL");}

    // <optgroup> of the previous <option>
    $previous = "";

    // variable to set the first group
    $first_group = true;
	
while (odbc_fetch_row($rs))
{
  $userid=odbc_result($rs,"userid");
  $name=odbc_result($rs,"Name");
  $lastname=odbc_result($rs,"lastname");
  $userdeptid=odbc_result($rs,"User_Group");
  $icnumber=odbc_result($rs,"ic");
  $deptname=odbc_result($rs,"gName");


        // if this <option> changed <optgroup> from the previous one,
        // then add a new <optgroup>
        if ($deptname != $previous) {
            if (!$first_group) {
                echo '</optgroup>';
            } else {
                $first_group = false;
            }
            echo '<optgroup label="'.$deptname.'">';
            $previous = $deptname;
        }

        // echo the current <option>
 

	  echo "<option value='".$icnumber."'>".$name." ".$lastname."</option>";
	
	} 

    // close the last <optgroup> tag
    echo '</optgroup>';
    // close the last <select> tag
    echo '</select></div>';



echo "<div class='form-group'>
    <label for='myyear'>  Year :</label>";

    $current_year = date('Y')*1;
    echo '<select id="myyear" name="myyear" onchange="showReport()" class="myyear form-control">';
    echo '<option value="">Select Year</option>';
    do {
        echo '<option value="'.$current_year.'">'.$current_year.'</option>';
        $current_year--;
    }
    while ($current_year >= $starting_year);
    echo '</select></div>';

    ?>
		<div class='form-group'>
    <label for='mymonth'>  Month :</label>
  			<select name="mymonth" id="mymonth" onchange="showReport()" class="mymonth form-control">
<option value="">Select Month</option>
      <option value="01">January</option>
      <option value="02">February</option>
      <option value="03">March</option>
      <option value="04">April</option>
      <option value="05">May</option>
      <option value="06">June</option>
      <option value="07">July</option>
      <option value="08">August</option>
      <option value="09">September</option>
      <option value="10">October</option>
      <option value="11">November</option>
      <option value="12">December</option>
      </select> </div>
		
	
</form>
</main>
</div>

<div class='container' id="txtHint"></div>

<?php

echo "</div>";
echo "<script src='assets/js/bootstrap.bundle.min.js'></script>";
echo "</body></html>"; 

?>
