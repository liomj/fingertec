<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description:  PHP/MS Access/ODBC Attendance Report For Vigilance Fingerprint Time Clock VT300 Device (VAMS 3.7.1)

include "config.php";
echo "<html><head>";
echo "<title>Staff Attendance Report</title>";
echo "<meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
echo "<link rel='stylesheet' href='assets/css/bootstrap.min.css' crossorigin='anonymous'>";
echo "<script src='assets/js/jquery.min.js'></script>";
echo "<link href='assets/css/select2.min.css' rel='stylesheet' />";
echo "<link href='assets/css/select2-bootstrap-5-theme.min.css' rel='stylesheet' />";
echo "<script src='assets/js/select2.min.js'></script>";
echo "<link href='assets/css/dataTables.bootstrap5.min.css' rel='stylesheet'>";
echo "<script src='assets/js/jquery.dataTables.min.js'></script>";
echo "<script src='assets/js/dataTables.bootstrap5.min.js '></script>"; 
echo "<link href='assets/css/navbar-top-fixed.css' rel='stylesheet'>";
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    var groupColumn = 2;

    var table = $('#userlist').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn },
			
        ],
        "order": [[2, 'desc' ]],
		"search": {
		"regex": true,
		"smart": false
		},
        "displayLength": 25,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="6">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );
	
	 $('#table-filter').on('change', function(){
       table.search(this.value).draw();   
    });
 
    // Order by the grouping
    $('#userlist tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
            table.order( [ groupColumn, 'desc' ] ).draw();
        }
        else {
            table.order( [ groupColumn, 'asc' ] ).draw();
        }
    } );
} );
</script>

<?php
echo "</head><body>";
include "menu.php";
echo "<main class='container'>";
echo "<div class='p-5'>";
echo "<br><span style='text-transform:uppercase'><b>Staff Directory</b></span><br><br>";
?>

<p>
<div class="form-group">
  <label for="department">Select Department:</label>
<select id="table-filter" class="form-control">
<option value="">Show All</option>


   <?php
           
$getdept="SELECT 
  user_group.id,
  user_group.gName,
  user_group.parentId 
  FROM user_group";
  
  $deptrs = odbc_exec($conn, $getdept);
if (!$deptrs) {
    exit("Error in SQL Display");
}
	
while(odbc_fetch_row($deptrs)){
  $deptid=odbc_result($deptrs,"User_Group");
  $deptname=odbc_result($deptrs,"gName");
echo "<option value='$deptname'>--&nbsp;&nbsp;$deptname</option>";
}
?>
</select>
</div>
</p>
<div class="table-responsive">
<table id="userlist" class="table table-striped table-bordered" cellspacing="0">

    <thead>
        <tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Department</th>
			<th>Designation</th>
			<th>IC No</th>
			<th>User ID</th>
        </tr>
    </thead>
	    <tfoot>
        <tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Department</th>
			<th>Designation</th>
			<th>IC No</th>
			<th>User ID</th>
        </tr>
    </tfoot>
    <tbody>
	
	<?php	
$accessQuery="SELECT 
  user.userid,
  user.Name,
  user.lastname,
  user.User_Group,
  user.ic,
  user.designation,
  user_group.id,
  user_group.gName,
  user_group.parentId 
  FROM user AS user 
  INNER JOIN user_group AS user_group ON user.User_Group=user_group.id";

		
$rs = odbc_exec($conn, $accessQuery);
if (!$rs) {
    exit("Error in SQL Display");
}
	
while(odbc_fetch_row($rs)){
  $userid=odbc_result($rs,"userid");
  $name=odbc_result($rs,"Name");
  $lastname=odbc_result($rs,"lastname");
  $userdeptid=odbc_result($rs,"User_Group");
  $icnumber=odbc_result($rs,"ic");
  $designation=odbc_result($rs,"designation");
  $deptname=odbc_result($rs,"gName");
        echo "<tr>";
		echo "<td>$name </td>";	
		echo "<td>$lastname </td>";	
	    echo "<td><b>$deptname</b></td>";
		echo "<td>$designation</td>";
		echo "<td>$icnumber</td>";
		echo "<td>$userid</td>";
        echo "</tr>";
}
		
		
     ?>
    </tbody>
</table>
</div>
<?php
echo "</main></div>";
echo "<script src='assets/js/bootstrap.bundle.min.js'></script>";
echo "</body></html>"; 

?>
