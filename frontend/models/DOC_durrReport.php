<style>
	.tos{  
	  background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
	  background-image: -webkit-linear-gradient(top, #eeeeee, #cccccc);
	  background-image: -moz-linear-gradient(top, #eeeeee, #cccccc);
	  background-image: -ms-linear-gradient(top, #eeeeee, #cccccc);
	  background-image: -o-linear-gradient(top, #eeeeee, #cccccc);
	  background-image: linear-gradient(top, #eeeeee, #cccccc);
	}

	th{color:black;}
	.input-group .form-control { height:27px; width:80%}
	.input-group-addon {height:26px !ipmortant;}
	.container{margin-left:0 !important; width:100% !important;}

	table th{color:white !important;}
	table input,select{color:black !important;}
	.generate{
		width: 100px;
		height: 30px;
		color:black !important;
		font-size:12px;
	}
	th {
		background: #006699 !ipmortant;
	}
	#example .form-control{
		height: 28px !important;
		padding:0px !important;
		text-align:center;
	}
	#example .text_filter,select{color:gray !important;}
	.tds{ color: black !important;}
	thead input, button, select, textarea{ font-size:10px !important;}
	#ui-datepicker-div{font-size:11px;}
</style>

<?php
//.tds{background:#122f83 !important;}
//file location /opt/processmaker/workflow/engine/methods/reports
$path=realpath(__DIR__ . '/../../cases');
require_once($path."/dbcon.php");
$lib=realpath(__DIR__ . '/../');
require_once($lib."/libs.php"); // contans js files and other common files
global $dbo;
$userId = isset( $_SESSION['USER_LOGGED'] ) ? $_SESSION['USER_LOGGED'] : 0;
$ticket_id = isset($_POST['ticket_id']) ? $_POST['ticket_id'] :-1;
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] :-1;
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] :-1;
$initial = isset( $_POST['initial'] ) ? $_POST['initial'] : 0;

//$data=$sth->fetchAll();
if($initial==0){  // first time initialization only
	if(2>=1){
	?>
		<div class='top' id='selectionArea'>
			<div class='innerdiv'>
				<table class='tos' width='100%'>
					<tr >
						<td align="center"  style='font-weight:bold; font-size:14px; font-variant:Small-Caps'>Document Upload, Replacement and Remove</td>
						<td align="center" style='padding-left:5px;'>
							<div class='input-group date' id='datetimepicker6'>
								<input placeholder='From date' type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>				
						</td>
						<td align="center" style='padding-left:5px;'>	
							<div class='input-group date' id='datetimepicker7'>
								<input type='text' placeholder='To date' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</td>
						<td style='text-align:center; width:20%'><input class='generate' type="button" onclick="generateReport();" value="Generate"/></td>
					</tr>
				</table>
				<iframe id="txtArea1" style="display:none"></iframe>
			</div>
		</div> 
		<?php }
		else die("No data to display ...");
} 
//==================== END ===============================================================
?>
<div id='details'>
<?php
if($initial!=0){
	$from_date=$from_date." 00:00:00";
	$to_date=$to_date." 23:59:59";
	 $qry="SELECT doc_filename,doc_title,dcr_ref,app_uid,requested_by AS uploaded_by,uploaded_date,process_type,doc_status,reason_for_decision,approved_date FROM DOC_HISTORY WHERE uploaded_date BETWEEN :fromDate AND :toDate
		UNION 
		SELECT 
		  doc_filename,
		  doc_title,
		  dcr_ref,
		  app_uid,
		  uploaded_by,
		  uploaded_date,
		  CASE
			WHEN doc_replace_id IS NULL 
			THEN 'REPLACE' 
			ELSE 'UPLOAD' 
		  END AS process_type,
		  CASE
			WHEN doc_status IS NULL 
			THEN 'Pending' 
		  END AS doc_status,
		  reason_for_decision,
		  approved_date 
		FROM
		  DOC_UPLOAD_LIST WHERE uploaded_date BETWEEN :fromDate AND :toDate
		  
		 UNION
		 SELECT DM.doc_filename,DM.doc_title,DOC_REMOVE_LIST.dcr_ref,DOC_REMOVE_LIST.app_uid,requested_by AS uploaded_by,requested_date AS uploaded_date,'REMOVE','Pending',NULL AS reason_for_decision,NULL AS approved_date FROM `DOC_REMOVE_LIST`
		 JOIN `DOC_MASTER` DM ON doc_remove_id=DM.id WHERE uploaded_date BETWEEN :fromDate AND :toDate";
	$sth = $dbo->prepare($qry);
	$sth->bindParam(':fromDate', $from_date, PDO::PARAM_STR);
	$sth->bindParam(':toDate', $to_date, PDO::PARAM_STR);
	$sth->execute();
	?>
	<table id="example" border='1' class="display tg compact" width="99%" cellspacing="0">
		<thead>
			<tr id='filterrow'>
				<th class='tds'>Reference No.</th>
				<th class='tds'>Document Name</th>
				<th class='tds'>Document Title</th>
				<th class='tds'>PIAF/DCR No.</th>
				<th class='tds'>Type of Request</th>
				<th class='tds'>Date Requested</th>
				<th class='tds'>Name of Requestor</th>
				<th class='tds'>Status</th>
				<th class='tds'>Date of Approval/Rejection</th>
				<th class='tds'>Remarks</th>
			</tr>	
			<tr>
				<th>Reference No.</th>
				<th>Document Name</th>
				<th>Document Title</th>
				<th>PIAF/DCR No.</th>
				<th>Type of Request</th>
				<th>Date Requested</th>
				<th>Name of Requestor</th>
				<th>Status</th>
				<th>Date of Approval/Rejection</th>
				<th>Remarks</th>
				
			</tr> 
		</thead>
		<tbody>
		<?php
			$BoxColor="";
		$i=0;
		foreach ($sth->fetchAll() as $rows){
			$i++;
			$uploaderId=$rows['uploaded_by'];
			$app_uid=$rows['app_uid'];
			$qryUser="SELECT full_name FROM SCI_USERTABLE WHERE USR_UID='$uploaderId'";
			$sth = $dbo->prepare($qryUser);
			$sth->execute();
			$aUser=$sth->fetch();
			$userName=$aUser['full_name'];
			$qryCase="SELECT APP_NUMBER  FROM APPLICATION WHERE APP_UID ='$app_uid' ";
			$sth = $dbo->prepare($qryCase);
			$sth->execute();
			$aCase=$sth->fetch();
			$caseNumber=$aCase['APP_NUMBER'];
			
			echo "<tr>";
			echo "<td width='10%' >".$caseNumber."</td>";
			echo "<td width='5%' >".$rows['doc_filename']."</td>";
			echo "<td width='5%' >".$rows['doc_title']."</td>";
			echo "<td width='5%' >".$rows['dcr_ref']."</td>";
			echo "<td width='5%' >".$rows['process_type']."</td>";
			echo "<td width='10%' >".$rows['uploaded_date']."</td>";
			echo "<td width='5%' >".$userName."</td>";
			echo "<td width='5%' >".$rows['doc_status']."</td>";
			echo "<td width='15%' >".$rows['approved_date']."</td>";
			echo "<td width='5%' >".$rows['reason_for_decision']."</td>";
			echo "</tr>";
		}?>
		</tbody>
	</table>
<?php } ?>
</div>
<script>
$(function () {
	
    $('#datetimepicker6').datetimepicker({format: "YYYY-MM-DD"});
    $('#datetimepicker7').datetimepicker({
		format: "YYYY-MM-DD",
        useCurrent: false //Important! See issue #1075
    });
    $("#datetimepicker6").on("dp.change", function (e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function (e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
});
$(document).ready(function() {
    /*   $('#example tr#filterrow th').each( function () {
        var title = $('#example thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
*/
    // DataTable
   var table= $('#example').dataTable({ dom: 'lBfrtip',buttons: ['copy','print',{extend: 'excelHtml5',title: 'docSearch'},{extend: 'csvHtml5',title: 'docSearch'}],"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]  }).columnFilter({sPlaceHolder : 'head:before', "aoColumns": [{ "type": "text" }, { "type": "text" }, { "type": "text" }, { "type": "text" }, { "type": "select" },{ "type": "text" }, { "type": "text" },{ "type": "select" }, { "type": "text" }, { "type": "text" }]});
    // Apply the search

} );
function generateReport(){
	

	var dateFrom = $("#datetimepicker6").find("input").val();
	var dateTo = $("#datetimepicker7").find("input").val();
	$('#details').html("<div class='loader' alt='Loading...'></div>");	
	$.post("../docs/DOC_durrReport.php",{ 'from_date':dateFrom,'to_date':dateTo,'initial':1},function (res){
		$('#details').html(res); 
		//document.getElementById("btnExport").style.display = "block";
	} ); 

	var table= $('#example').dataTable({ dom: 'lBfrtip',buttons: ['copy','print',{extend: 'excelHtml5',title: 'docSearch'},{extend: 'csvHtml5',title: 'docSearch'}],"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]  }).columnFilter({sPlaceHolder : 'head:before', "aoColumns": [{ "type": "text" }, { "type": "text" }, { "type": "text" }, { "type": "text" },{ "type": "select" }, { "type": "text" }, { "type": "text" },{ "type": "select" }, { "type": "text" }, { "type": "text" }]});
}

</script>