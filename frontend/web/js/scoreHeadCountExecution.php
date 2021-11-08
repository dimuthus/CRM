<?php
//file location /opt/processmaker/workflow/engine/methods/reports
if (! class_exists( 'pmFunctions' )) {
    G::LoadClass( 'pmFunctions' );
}
require_once(__DIR__ . '/../libs.php');

$userId = isset( $_SESSION['USER_LOGGED'] ) ? $_SESSION['USER_LOGGED'] : 0;
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] :-1;
$initial = isset( $_POST['initial'] ) ? $_POST['initial'] : 0;

$userQry="SELECT full_name FROM SCI_USERTABLE WHERE USR_UID='$userId'";
$user_name = executeQuery($userQry,"rp")[1]['full_name'];
$isSAET=checkUserGroup($userId,'SAET');
$whrCondition="WHERE `vw_employees`.`USR_STATUS`='ACTIVE'";
if (!$isSAET)
$whrCondition="WHERE `vw_employees`.`USR_UID`= '$userId' AND `vw_employees`.`USR_STATUS`='ACTIVE' ";	
//date_default_timezone_set("Asia/Kuala_Lumpur");
$reportDate="";
if ($from_date !=-1)
	$reportDate=$from_date;
else
   $reportDate="2020-07-01";

$query="SELECT
   `vw_employees`.`Department`
  ,COUNT(`vw_employees`.`USR_UID`) AS totalHeadCount
,COUNT(`SAET_BAND_SCORE`.`LISTENING_TEST_DATE`) AS LISTENING
,COUNT(`SAET_BAND_SCORE`.`READING_TEST_DATE`) AS READING
,COUNT(`SAET_BAND_SCORE`.`WRITING_TEST_DATE`) AS WRITING
FROM
    `vw_employees`
   LEFT OUTER JOIN `SAET_BAND_SCORE` 
        ON (`vw_employees`.`USR_UID` = `SAET_BAND_SCORE`.`USR_UID`)
        WHERE   `vw_employees`.`USR_STATUS`='ACTIVE' AND 
        (`SAET_BAND_SCORE`.`LISTENING_TEST_DATE` < ' $reportDate' OR 
        `SAET_BAND_SCORE`.`READING_TEST_DATE` < ' $reportDate' OR 
        `SAET_BAND_SCORE`.`WRITING_TEST_DATE` < ' $reportDate')
        GROUP BY `vw_employees`.`Department`
           ORDER BY    `vw_employees`.`Department` ";
$sth = executeQuery($query,'rp');
echo"<div class='title'><img src='/images/titleImg2.png' style='vertical-align:text-bottom;display:inline;'>
<p>Department specific head counts till - " .$reportDate."</p></div>";
?>
<?php 
if($initial==0){ ?>
<table><tr><td>
 <div class='input-group date' id='datetimepicker6'>
                  <input placeholder='Report Date' type='text' class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div></td><td>
				
				          <input class='generate' type="button" onclick="generateReport();" value="Generate"/></td></tr></table>
<?php } ?>
						  
<div id='details'>
<?php


echo "<table border='1' style='width:100%' id='tblleave' class='display'>
<thead><tr style='background-color:#1546b0; color: #ffffff;'>
<th>Department/Project</th>
<th>Total Head Count</th>
<th>Listening</th>
<th>Reading</th>
<th>Writing</th>
</tr></thead><tbody>";
$BoxColor="";
$i=0;
$overAll=0;
foreach ($sth as $rows){
		echo "<tr>";
	    echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['Department']."</td>";
		echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['totalHeadCount']."</td>";
	 	echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['LISTENING']."</td>";
		echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['READING']."</td>";
		echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['WRITING']."</td>";
		echo "</tr>";
}
echo "</tbody></table>";


?></div>
<script>

$('#tblleave').DataTable({  "aaSorting": [], dom: 'lBfrtip',buttons: ['print',{extend: 'excelHtml5',title: 'My SAET Report'},{extend: 'csvHtml5',title: 'My SAET Report'},'copy'],"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]  });

    $('#datetimepicker6').datetimepicker({format: "YYYY-MM-DD"});

	
function generateReport(){

	var dateFrom = $("#datetimepicker6").find("input").val();
   if (dateFrom.length==0){
		alert('From date is required');
		return false;
	}
	
	$('#details').html("<div class='loader' alt='Loading...'></div>");	
	$.post("../saet/scoreHeadCountExecution.php.php",{ 
	'from_date':dateFrom,
	'initial':1},function (res){
	$('#details').html(res); 

		
	} ); 

}
</script>
