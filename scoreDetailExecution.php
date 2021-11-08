<?php
//file location /opt/processmaker/workflow/engine/methods/reports
if (! class_exists( 'pmFunctions' )) {
    G::LoadClass( 'pmFunctions' );
}
require_once(__DIR__ . '/../libs.php');

$userId = isset( $_SESSION['USER_LOGGED'] ) ? $_SESSION['USER_LOGGED'] : 0;
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] :-1;
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] :-1;
$testId = isset($_POST['testId']) ? $_POST['testId'] :1;
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

$filterDates="";
$displayDates="";
if ($testId==1){
	$filterDates="`SAET_BAND_SCORE`.`LISTENING_TEST_DATE`";
	$displayDates="Listening";
}
else if ($testId==2)
{
	$filterDates="`SAET_BAND_SCORE`.`READING_TEST_DATE`";
	$displayDates="Reading";
}
else
{
	$filterDates="`SAET_BAND_SCORE`.`WRITING_TEST_DATE`";
	$displayDates="Writing";
}
$query="SELECT
   `vw_employees`.`Department`
  ,`SCI_USERTABLE`.`full_name` AS testTaker
  , SCI_USERTABLE.`USR_UID` AS userId
  ,$filterDates AS testDate
FROM
    `vw_employees`
   LEFT OUTER JOIN `SAET_BAND_SCORE` 
        ON (`vw_employees`.`USR_UID` = `SAET_BAND_SCORE`.`USR_UID`)
   INNER JOIN SCI_USERTABLE
   ON SCI_USERTABLE.`USR_UID`=`SAET_BAND_SCORE`.`USR_UID`
        WHERE   `vw_employees`.`USR_STATUS`='ACTIVE' AND 
        ($filterDates BETWEEN ' $from_date 00:00:00' AND  '$to_date 23:59:59' )
        GROUP BY `vw_employees`.`Department`,`vw_employees`.`USR_UID`
           ORDER BY    `vw_employees`.`Department`  ";
		//   die($query);
$sth = executeQuery($query,'rp');
echo"<div class='title'><img src='/images/titleImg2.png' style='vertical-align:text-bottom;display:inline;'>
<p>Department specific head counts till - " .$reportDate."</p></div>";


$qry="SELECT ID,DESCRIPTION FROM SAET_EVALUATION_TYPE WHERE ID !=4 ORDER BY ID ";
$st =executeQuery($qry,'rp');


?>
<?php 
if($initial==0){ ?>
<table><tr><td>
 <div class='input-group date' id='datetimepicker6'>
                  <input placeholder='Report Date' type='text' class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div></td>
				<td>
				<div class='input-group date' id='datetimepicker7'>
                  <input placeholder='Report Date(TO)' type='text' class="form-control" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
				</td>
				  <td>
          <select id='testList' class='List' style='padding:5px; width:90%;'>
            <?php foreach($st as  $data){ 
					    echo "<option value=".$data['ID']." selected>".$data['DESCRIPTION']."</option>";
					  }?>
          </select>
      
				
				</td>
				<td>
				
				          <input class='generate' type="button" onclick="generateReport();" value="Generate"/></td></tr></table>
<?php } ?>
						  
<div id='details'>
<?php


echo "<table border='1' style='width:100%' id='tblleave' class='display'>
<thead><tr style='background-color:#1546b0; color: #ffffff;'>
<th>Department/Project</th>
<th>Test Take Name</th>
<th>".$displayDates." Test Date</th>
</tr></thead><tbody>";
$BoxColor="";
$i=0;
$overAll=0;
foreach ($sth as $rows){
$userID=$rows['userId'];
		    $qryForCaseNumber="SELECT
  `APPLICATION`.`APP_NUMBER` AS  APP_NUMBER

 FROM
  `wf_workflow`.`SAET_APPLICATIONS`
  INNER JOIN `wf_workflow`.`APPLICATION`
    ON (
      `SAET_APPLICATIONS`.`APP_UID` = `APPLICATION`.`APP_UID`
    )
    WHERE  `SAET_APPLICATIONS`.`USR_UID`=".$userID."  AND    `SAET_APPLICATIONS`.`EVALUATION_TYPE_ID`=".$testId."";
	//$caseNumber = executeQuery($qryForCaseNumber,"rp")[1]['APP_NUMBER'];
		echo "<tr>";
	    echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['Department']."</td>";
		echo "<td style='background-color:#fef8f2; text-align:center;'>".$rows['testTaker']."</td>";
	 	echo "<td style='background-color:#fef8f2; text-align:center;'> ".$rows['testDate']."</td>";
		
		echo "</tr>";
}
echo "</tbody></table>";


?></div>
<script>

$('#tblleave').DataTable({  "aaSorting": [], dom: 'lBfrtip',buttons: ['print',{extend: 'excelHtml5',title: 'My SAET Report'},{extend: 'csvHtml5',title: 'My SAET Report'},'copy'],"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]  });

    $('#datetimepicker6').datetimepicker({format: "YYYY-MM-DD"});
	$('#datetimepicker7').datetimepicker({format: "YYYY-MM-DD"});

	
function generateReport(){

	var dateFrom = $("#datetimepicker6").find("input").val();
	var dateTo = $("#datetimepicker7").find("input").val();
    var testId = $("#testList option:selected").val();
   if (dateFrom.length==0){
		alert('From date is required');
		//toastr.success("From date is required", "Required");
		return false;
	}
	else if (dateTo.length==0){
		alert('To date is required');
		return false;
	}
	$('#details').html("<div class='loader' alt='Loading...'></div>");	
	$.post("../saet/scoreDetailExecution.php.php",{ 
	'from_date':dateFrom,
		'to_date':dateTo,
		'testId':testId,
	'initial':1},function (res){
	$('#details').html(res); 

		
	} ); 

}
</script>
