<script type="text/javascript" src="/lib/pmdynaform/libs/dtjavascript/jquery.validate.js"></script>
<?php
//use this url to read ->>>>>http://10.1.27.108:88/sysworkflow/en/classic/cases/leaveBalance.php
//file location /opt/processmaker/workflow/engine/methods/cases
if (! class_exists( 'pmFunctions' )) {
    G::LoadClass( 'pmFunctions' );
}
$status='ACTIVE';
if(isset($_POST['status'])) $status=$_POST['status'];
global $dbo;
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;

$query="SELECT dept_name,pwd,upload_status,error_count FROM SPK_PWDS_UPLOADED WHERE application_id='$appUid'";
//echo ($query.$userId);

$res=executeQuery($query);
//$rows = $sth->fetchAll();
echo "<table border='1' style='width:100%'  class='display' id='tblUsers'>
<thead style='background-color:#1546b0; color: #ffffff;'>
<tr>
	<th>SEQ</th>
	<th >DEPARTMENT</th>
	<th>PASSWORD</th>
	<th>STATUS</th>
 </tr>

</thead><tbody>";
$BoxColor="";
$i=0;

foreach ($res as $rows){
		$i++;
		//$testUserId=$rows['USR_UID'];
		$error_count=$rows['error_count'];
		$error="";
		if ($error_count)
			$error="class='error'";
		echo "<tr ".$error.">";
		echo "<td style='background-color:#fef8f2; '>".$i."</td>";
       	echo "<td style='background-color:#eff3fe; '>".$rows['dept_name']."</td>";
		echo "<td style='background-color:#fef8f2; '>".$rows['pwd']."</td>";
		echo "<td $error >".$rows['upload_status']."</td>";
		echo "</tr>";
		
		

	 ?>


	  </div>
	  </div>
		
	<?php	}
echo "</tbody></table>";

?>


     
<div id='jmodel'><form id="file_form_ini">    <input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 12px;" type='text' id='expTypeOther' name='expTypeOther' required=""  >
                <span style="color:red;" id="error_expTypeOther" ></span>
</form></div>

</div>
<script>
$( document ).ready(function() {
    	$('.tabs').tabs();
		
/*
$('#jmodel').dialog({
    'title': 'My Dialog Header',
    'buttons': {
        'add': function(event) {
			if (!$('#file_form_ini').valid()){
				return false;
			}
          
        }
    }
});
*/


});


</script>
<style>

div.ansText {
	background-color: lightblue;
    width: 100%;
    height: 230px;
	overflow-y: scroll;
    padding: 0px;
    margin: 0 0 10px 0;
    border: 1px solid #aaaaaa;
}
.tot {
	background-color: orange;
}
.avg {
	background-color: lightblue;
}
.bandscore{
	background-color: lightgreen;
}
.error{
	background-color: pink !important;
}
</style>
