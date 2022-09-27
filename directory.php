<?php
//  Author: Lio MJ 
//  URL: (https://github.com/liomj/)
//  Description:  PHP/MS Access/ODBC Attendance Report For Vigilance Fingerprint Time Clock VT300 Device (VAMS 3.7.1)

include "config.php";
echo "<html><head>";
echo "<title>Staff Attendance Report</title>";
echo "<meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
echo "<link rel='stylesheet' href='assets/css/bootstrap.min.css' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' 
crossorigin='anonymous'>";
echo "<script src='assets/js/jquery.min.js'></script>";
echo "<link href='assets/css/select2-bootstrap.css' rel='stylesheet' />";
echo "<link href='assets/css/select2.min.css' rel='stylesheet' />";
echo "<script src='assets/js/select2.min.js'></script>";
echo "<link rel='stylesheet' type='text/css' href='assets/css/datatables.min.css'/>
<script type='text/javascript' src='assets/js/datatables.min.js'></script>";
echo "<link href='navbar-top-fixed.css' rel='stylesheet'>";
?>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    var groupColumn = 1;

    var table = $('#userlist').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn },
			
        ],
        "order": [[1, 'desc' ]],

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

<table id="userlist" class="table table-striped table-bordered" cellspacing="0">

    <thead>
        <tr>
			<th>Name</th>
			<th>Department</th>
			<th>IC No</th>
			<th>User ID</th>
        </tr>
    </thead>
	    <tfoot>
        <tr>
			<th>Name</th>
			<th>Department</th>
			<th>IC No</th>
			<th>User ID</th>
        </tr>
    </tfoot>
    <tbody>
	
	<?php	
$accessQuery="SELECT 
  user.userid,
  user.Name,
  user.User_Group,
  user.ic,user_group.id,
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
  $userdeptid=odbc_result($rs,"User_Group");
  $icnumber=odbc_result($rs,"ic");
  $deptname=odbc_result($rs,"gName");
        echo "<tr>";
		echo "<td>$name </td>";	
	    echo "<td><b>$deptname </b></td>";
		echo "<td>$icnumber</td>";
		echo "<td>$userid</td>";
        echo "</tr>";
}
		
		
     ?>
    </tbody>
</table>

<?php
echo "</main></div>";
echo "</body></html>"; 

?>
