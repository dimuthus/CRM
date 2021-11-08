<style>
	#ticketId {height: 24px; color:black; color:black !important;}
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
//file location /opt/processmaker/workflow/engine/methods/reports
$path=realpath(__DIR__ . '/../../cases');
require_once($path."/dbcon.php");
$lib=realpath(__DIR__ . '/../');
require_once($lib."/libs.php"); // contans js files and other common files
//require_once($lib."/report_style.php"); // contans css code for datatable display
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
	
<a href="http://dev.campus.scicom.com.my/moodle/login/index.php">Visit LMS!</a>
		<div class='top' id='selectionArea'>
			<div class='innerdiv'>
				<table class='tos' width='100%'>
					<tr >
						<td align="center"  style='font-weight:bold; font-size:14px; font-variant:Small-Caps'>Ticket Id:</td><td><input class='' id='ticketId' type="text" value=""/></td>
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
$qry="SELECT * FROM VW_IT_FN_TICKET_STATUS WHERE caseNumber=:ticketId OR (opened_date BETWEEN :fromDate AND :toDate)";
$sth = $dbo->prepare($qry);
$sth->bindParam(':ticketId', $ticket_id, PDO::PARAM_STR);
$sth->bindParam(':fromDate', $from_date, PDO::PARAM_STR);
$sth->bindParam(':toDate', $to_date, PDO::PARAM_STR);
$sth->execute();
?>
<table id="example" border='1' class="display tg compact" width="99%" cellspacing="0">
    <thead>
		<tr id='filterrow'>
			<th class='tds'>Requestor Name</th>
			<th class='tds'>Ticket ID</th>
			<th class='tds'>Opened Date</th>
			<th class='tds'>Closed Date</th>
			<th class='tds'>Department/Project</th>
			<th class='tds'>Physical Location</th>
			<th class='tds'>Extension</th>
			<th class='tds'>Problem Type</th>
			<th class='tds'>Description</th>
			<th class='tds'>Priority</th>
			<th class='tds'>Last Handler</th>
			<th class='tds'>Current Handler</th>
			<th class='tds'>Status</th>
		</tr>
		<tr>
			<th class='tdh'>Requestor Name</th>
			<th class='tdh'>Ticket ID</th>
			<th class='tdh'>Opened Date</th>
			<th class='tdh'>Closed Date</th>
			<th class='tdh'>Department/Project</th>
			<th class='tdh'>Physical Location</th>
			<th class='tdh'>Extension</th>
			<th class='tdh'>Problem Type</th>
			<th class='tdh'>Description</th>
			<th class='tdh'>Priority</th>
			<th class='tdh'>Last Handler</th>
			<th class='tdh'>Current Handler</th>
			<th class='tdh'>Status</th>
		</tr>
	</thead>
    <tbody>
	<?php
		$BoxColor="";
	$i=0;
	$assignTo="";
	$lastHdUser="";
	foreach ($sth->fetchAll() as $rows){
		$i++;
		
		if ($rows['escalation_flag']==1){
		  $qry="SELECT `SCI_USERTABLE`.`username` FROM
				`IT_FN_ESCALATION`
				INNER JOIN `SCI_USERTABLE` 
				ON (`IT_FN_ESCALATION`.`escalated_to` = `SCI_USERTABLE`.`USR_UID`)
				WHERE  `IT_FN_ESCALATION`.`ticket_id`=:ticket_id ORDER BY `IT_FN_ESCALATION`.`id` DESC LIMIT 1";
			$sth = $dbo->prepare($qry);
			$sth->bindParam(':ticket_id', $rows['ticket_id'], PDO::PARAM_STR);
			$sth->execute();
			$aUser=$sth->fetch();
			$assignTo=$aUser['username'];
		}
		else{
		   $assignTo=$rows['username'];
		}
   
	
		
		if ($rows['statusId']==5 || $rows['statusId']==12 || $rows['statusId']==6 || $rows['statusId']==7 ){
				$lastHdUser=$rows['last_updated_by'];
		}
		else{
		$lastHdUser=$rows['current_handler'];
		}
		
        echo "<tr>";
		echo "<td width='10%' >".$rows['creator']."</td>";
        echo "<td width='5%' ><a href='#' onclick='ticketDetailsById(".$rows['ticket_id'].",".$rows['statusId'].");'>".$rows['caseNumber']."</a></td>";
	    echo "<td width='5%' >".$rows['opened_date']."</td>";
		echo "<td width='5%' >".$rows['closed_date']."</td>";
		echo "<td width='5%' >".$rows['department']."</td>";
		echo "<td width='5%' >".$rows['physcialLocation']."</td>";
		echo "<td width='5%' >".$rows['extension']."</td>";
		echo "<td width='5%' >".$rows['ProblemType']."</td>";
		echo "<td width='15%' >".$rows['problem_desc']."</td>";
		echo "<td width='5%'>".$rows['Priority']."</td>";
		echo "<td width='5%' >".$lastHdUser."</td>";
		echo "<td width='5%' >".$rows['current_handler']."</td>";
	    echo "<td width='5%' >".$rows['TicketStatus']."</td>";
		//echo "<td >".$rows['solution']."</td>";
	
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
        
    // DataTable
    var table= $('#example').dataTable({ dom: 'lBfrtip',buttons: ['copy','print',{extend: 'excelHtml5',title: 'docSearch'},{extend: 'csvHtml5',title: 'ticketSearch'}],"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]  }).columnFilter({sPlaceHolder : 'head:before', "aoColumns": [{ "type": "text" }, { "type": "text" }, { "type": "text" }, { "type": "text" },{ "type": "text" },{ "type": "text" }, { "type": "text" },{ "type": "text" }, { "type": "text" }, { "type": "select" },{ "type": "text" },{ "type": "text" },{ "type": "select" }]});
    // Apply the search
} );
function generateReport(){
	
	var ticketId = document.getElementById('ticketId').value;
	var dateFrom = $("#datetimepicker6").find("input").val();
	var dateTo = $("#datetimepicker7").find("input").val();
	$('#details').html("<div class='loader' alt='Loading...'></div>");	
	$.post("../helpdesk/HD_ticketSearch.php",{'ticket_id':ticketId, 'from_date':dateFrom,'to_date':dateTo,'initial':1},function (res){
		$('#details').html(res); 
		
	} ); 

	var table= $('#example').dataTable({ dom: 'lBfrtip',buttons: ['copy','print',{extend: 'excelHtml5',title: 'docSearch'},{extend: 'csvHtml5',title: 'ticketSearch'}],"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]  }).columnFilter({sPlaceHolder : 'head:before', "aoColumns": [{ "type": "text" }, { "type": "text" }, { "type": "text" }, { "type": "text" },{ "type": "text" },{ "type": "text" }, { "type": "text" },{ "type": "text" }, { "type": "text" }, { "type": "select" },{ "type": "text" },{ "type": "text" },{ "type": "select" }]});
	

}
function ticketDetailsById(ticketId,statusId){
  window.open('HD_ICTTicketDetail.php?ticketId='+ ticketId,"_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=20, left=20, width=800, height=660");
}
</script>