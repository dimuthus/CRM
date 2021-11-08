<?php
//file location /opt/processmaker/workflow/engine/methods/cases
G::LoadClass( 'pmFunctions' );
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
$appId  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$option =isset( $_POST['option'] ) ? $_POST['option']:0;
$UpdateDialog= "<script>
	function createUpdateDialog(title, text,option) {
		$(\"<div class='dialog' title='\" + title + \"'><p>\" + text + \"</p></div>\")
		.dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				'Confirm': function() {
					confirmUpdateData();
					$( this ).dialog( 'close' );
				},
				Cancel: function() {
					$( this ).dialog( 'close' );
				}
			}
		});
	}
</script>";

switch ($option){
	case 1:
		$query="SELECT
				STEP_ID,
				CAH.USR_UID,
				U.full_name,
				D.dept_name,
				S.designation_name,
				CAH.ACTION,
				CAH.REMARKS,
				CAH.CREATED_DATE
				FROM CRM_APP_HISTORY CAH
				INNER JOIN SCI_USERTABLE U ON U.USR_UID=CAH.USR_UID
				INNER JOIN SCI_DESIGNATION S ON S.id=U.designation_id
				INNER JOIN SCI_DEPARTMENT D ON D.id=U.department_id
				WHERE APP_UID='$appId'
				ORDER BY CAH.CREATED_DATE";
		//echo $query;
		$crmRes=executeQuery($query);

		echo "<table border='1' style='width:100%; border:1px solid #25272929'  ;name='requetTblName' id='requestTbl' class='display'>
		<thead><tr style='background-color:#357ebd; color: #ffffff;'>
		<th style='text-align:center;'>Step No.</th>
		<th style='text-align:center;'>Staff name</th>
		<th style='text-align:center;'>Staff Designation</th>
		<th style='text-align:center;'>Department</th>
		<th style='text-align:center;'>Staff Action</th>
		<th style='text-align:center;'>Staff Remarks</th>
		<th style='text-align:center;'>Response Date</th>
		</tr>
		</thead><tbody>";
		foreach ($crmRes as $rows){
			echo "<tr>
			<td style='background-color:#eff3fe; text-align:center;'>".$rows['STEP_ID']."</td>
			<td style='background-color:#fef8f2; text-align:center;'>".$rows['full_name']."</td>
			<td style='background-color:#eff3fe; text-align:center;'>".$rows['designation_name']."</td>
			<td style='background-color:#fef8f2; text-align:center;'>".$rows['dept_name']."</td>
			<td style='background-color:#eff3fe; text-align:center;'>".$rows['ACTION']."</td>
			<td style='background-color:#fef8f2; text-align:center;'>".$rows['REMARKS']."</td>
			<td style='background-color:#eff3fe; text-align:center;'>".$rows['CREATED_DATE']."</td>
			</tr>";
		}
		echo "</tbody></table>";
		break;
	case 2:
		$res=executeQuery("SELECT
		  COUNT(T.`ID`) AS rows
		FROM
		  `CRM_APP_ACTIVITY` T
		  INNER JOIN `CRM_APP_TASKS`
			ON CRM_APP_TASKS.ID = T.TASK_ID
		  INNER JOIN CRM_STATUS
			ON CRM_STATUS.ID = T.STATUS_ID
			AND CRM_STATUS.STATUS_TYPE = 'PROCESS'
		WHERE T.APP_UID ='$appId'");
		if(count($res)<=0){
			executeQuery("INSERT INTO CRM_APP_ACTIVITY (APP_UID,TASK_ID,USR_UID,STATUS_ID,PERCENTAGE,UPDATED_DATE) SELECT APP_UID,ID,'$userId','1','0.0',NOW() FROM CRM_APP_TASKS WHERE APP_UID='$appId'");
		}
		else if($res[1]['rows']<=0){
			executeQuery("INSERT INTO CRM_APP_ACTIVITY (APP_UID,TASK_ID,USR_UID,STATUS_ID,PERCENTAGE,UPDATED_DATE) SELECT APP_UID,ID,'$userId','1','0.0',NOW() FROM CRM_APP_TASKS WHERE APP_UID='$appId'");
		}
		$activityDetailsQry="SELECT
		  CRM_APP_TASKS.ID,
		  CRM_APP_TASKS.TASK,
		  CRM_STATUS.STATUS_NAME,
		  T.STATUS_ID,
		  T.COMMENT,
		  T.PERCENTAGE,
		  T.CREATED_DATE
		FROM
		  (SELECT
			*
		  FROM
			`CRM_APP_ACTIVITY`
		  WHERE `ID` IN
			(SELECT
			  MAX(`ID`)
			FROM
			  `CRM_APP_ACTIVITY`
			GROUP BY `TASK_ID`,
			  `APP_UID`)) AS T
		  INNER JOIN `CRM_APP_TASKS`
			ON CRM_APP_TASKS.ID = T.TASK_ID
		  INNER JOIN CRM_STATUS
			ON CRM_STATUS.ID = T.STATUS_ID
			AND CRM_STATUS.STATUS_TYPE='PROCESS'
		WHERE T.APP_UID ='$appId' ORDER BY T.TASK_ID";
		$activityDetails=executeQuery($activityDetailsQry);
		$status=executeQuery("SELECT ID, STATUS_NAME FROM CRM_STATUS WHERE IS_ACTIVE=1 AND STATUS_TYPE='PROCESS'");

		$num_of_tasks=count($activityDetails)>0?count($activityDetails):1;
		$res=executeQuery("SELECT SUM(PERCENT) AS PERCENTAGE FROM (SELECT MAX(PERCENTAGE) AS PERCENT FROM `CRM_APP_ACTIVITY` WHERE APP_UID='$appId'
		GROUP BY TASK_ID) X");
		$totalPercentage=0;
		if(count($res)>0)	{
			$percentage=$res[1]['PERCENTAGE'];
			$totalPercentage=$percentage/$num_of_tasks;
		}
		executeQuery("UPDATE CRM_APPLICATION SET PERCENTAGE='$totalPercentage',LAST_UPDATED_DATE=NOW() WHERE APP_UID='$appId'");

		$val= "<table border='1' style='width:100%; border:1px solid #25272929' id='crmTbl' class='display' >
		<thead style='background-color:#357ebd; color:white;'>
			<tr>
				<th>No.</th>
				<th>Activity</th>
				<th>Status</th>
				<th>Comment</th>
				<th>Percentage</th>
				<th>Created Date</th>
			</tr>
		</thead>
		<tbody>";

		$i=1;
		foreach($activityDetails as $data){
			$val.= "<tr>";
			$val.= "<td width='5%'>".($i++)."</td>";
			$val.= "<td id='".$data['ID']."'>".$data['TASK']."</td>";
			$val.= "<td>
				<select id='selectStatus'>";
			foreach($status as $rows){
					if($rows['ID']==$data['STATUS_ID'])
						$val.= "<option value=".$data['STATUS_ID']." selected>".$data['STATUS_NAME']."</option>";
					else $val.= "<option value=".$rows['ID'].">".$rows['STATUS_NAME']."</option>";
			}
			$val.= "</select>
			</td>";
			$val.= "<td width='10%'><textarea type='text'>".$data['COMMENT']."</textarea></td>";
			$val.= "<td width='10%'><input type='text' value=".$data['PERCENTAGE']." style='width:90%' class='numbersOnly'>%</td>";
			$val.= "<td width='15%'>".$data['CREATED_DATE']."</td>";
			$val.= "</tr>";
		}
		$val.= "</tbody></table>";
		$val.= "<div id='totalPercentage' name='field-totalPercentage' class='pmdynaform-field-label  form-group  col-lg-5  pmdynaform-view-label pmdynaform-field'>
				<label class=' control-label pmdynaform-label'>
					<span class='textlabel'>Over All Percentage Completed
					</span>
				</label>
				<div class='col-md-8 col-lg-8 pmdynaform-field-control'>
					<div id='totalPer' class='pmdynaform-label-options form-control'>
							<span title='".$totalPercentage."'>".$totalPercentage." %</span>
					</div>
				</div>
			</div>";
		$val.= "<button id='update' name='update' type=button class='btn btn-primary' onclick='updateData()'>
			<span>Update</span>
			</button>";
		$val.=$UpdateDialog;
		$val.= "<script>
		var rowIndex=[];
		$(document).ready(function() {

		});
		$('#crmTbl').dataTable();
		$('#crmTbl tr td').change(function(e){
			oldValue=parseInt(e.target.defaultValue);
			newValue=parseInt(e.target.value);
			if(oldValue!==newValue) {
				if(newValue<oldValue || newValue>100) e.target.value=oldValue;
				else {
					var self = $(this);
					row = self.closest('tr');
					rowIndex.push(row.index());
				}
			}
		});
		$('.numbersOnly').keyup(function () {
			if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
			   this.value = this.value.replace(/[^0-9\.]/g, '');
			}
		});

		function updateData(){
			if(rowIndex.length!==0){
				createUpdateDialog('Update Activity Dialog', 'Are you sure updating activity?','UPDATE');
			} else{
				alert ('No change has been done on your activity. Please make sure you update your activity.');
			}
		}
		function confirmUpdateData(){
			var TableData = new Array();
				$('#crmTbl tr').each(function(row, tr){
					var index=$(this).index();
					var found=searchItem(rowIndex,index);
					if(found){
						TableData.push({'TASK_ID' :$(tr).find('td:eq(1)').attr('id')
							,'TASK_STATUS' : $(tr).find('td:eq(2) select').val()
							,'COMMENT' :$(tr).find('td:eq(3) textarea').val()
							,'PERCENTAGE' :$(tr).find('td:eq(4) input').val()
						});
					}
				});
				$.post('../cases/crm_application.php', {option:'3',data:TableData},function (res){
					$('.loading2').show();
				$.post('../cases/crm_application.php',{option:'2'},function (res){
						$('.loading2').hide();
						$('#details').html('');
						var resp=$.parseJSON(res);
						$('#details').html(resp.data);
						var progress=parseInt(resp.totPercentage);
						if(progress<100)
						   $('#btnNext').hide();
						else $('#btnNext').show();
						$('#crmTbl').DataTable();
					 });
				});
		}
		function searchItem(array,index){
			for(i=0;i<array.length;i++) {
				if(array[i]==index) {
					return true;
				}
			}
			return false;
		}
		</script>";
		$res=array('data'=>$val,'totPercentage'=>$totalPercentage);
		echo (json_encode($res));
		break;
	case 3:
		if(isset($_POST['data'])){
			$data=$_POST['data'];
			foreach($data as $key=>$row){
				$id=$row['TASK_ID'];
				$status=$row['TASK_STATUS'];
				$comment=mysql_real_escape_string($row['COMMENT']);
				$per= $row['PERCENTAGE'];
				$qry="INSERT INTO CRM_APP_ACTIVITY (APP_UID,USR_UID,TASK_ID,STATUS_ID,PERCENTAGE,COMMENT,UPDATED_DATE) VALUES ('$appId','$userId','$id','$status','$per','$comment',NOW())";
				executeQuery($qry);
			}
		}
		break;
	case 4:
		// show email notification checklist
		$resNotification=executeQuery("SELECT CRM_STATUS.ID,`STATUS_NAME`,`EMAIL_SEND_DATE`,`EVENT_DATE`,ALREADY_SENT FROM `CRM_STATUS`
		LEFT JOIN `CRM_APP_EMAIL_NOTIFICATION` ON `CRM_APP_EMAIL_NOTIFICATION`.`STATUS_ID`=CRM_STATUS.`ID` AND
		`CRM_APP_EMAIL_NOTIFICATION`.`APP_UID`='$appId' WHERE  CRM_STATUS.`STATUS_TYPE`='NOTIFICATION'");

		echo "<table style='width:100%;' id='notificationTbl' class='display' >
		<thead style='color:#357ebd;'>
			<tr>
				<th>No.</th>
				<th>Notification</th>
				<th>Email Send Date</th>
				<th>Event Date</th>
				<th>Already Sent</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>";
		$index=1;
		foreach($resNotification as $notify){
			echo "<tr style='padding-bottom:2px;'>";
			echo "<td>".($index++)."</td>";
			echo "<td>".$notify['STATUS_NAME']."</td>";
			echo "<td>".$notify['EMAIL_SEND_DATE']."</td>";
			echo "<td>".$notify['EVENT_DATE']."</td>";
			$disabled='';
			if($notify['ALREADY_SENT']==1){
				echo "<td style='color:green'>YES</td>";
				$disabled='disabled';
			}
			else
				echo "<td style='color:red;'>NO</td>";
			echo "<td style='padding-bottom: 1px;'><button id='send_".$notify['ID']."' name='send_".$notify['ID']."' type=button style='padding:4px 10px 4px 10px' class='btn btn-primary' onclick='sendEmailNotification(this.id)' ".$disabled.">
				<span>Send</span>
				</button></td>";
			echo "</tr>";
		}
		echo "</tbody></table></br>";
		echo "<div  id='sendNotificationDiv' style='display:none'> Date:<input type='text' id='notifyDate' placeholder='YYYY-MM-DD'></div>"; // Don't put required here
		echo "<script>
		function sendEmailNotification(id){
			notifyId=id.split('_')[1];
			$('#sendNotificationDiv').dialog({
				resizable: false,
				modal: true,
				title: 'Send Email Notification',
				height: 250,
				width: 300,
				buttons: {
					'Confirm': function() {
						date=$('#notifyDate').val();
						sendEmailNotify(notifyId,date);
						$( this ).dialog( 'close' );
					},
					Cancel: function() {
						$( this ).dialog( 'close' );
					}
				}
			});
		}
		function sendEmailNotify(notifyId,eventDate){

			$.post('../cases/crm_application.php', {option:'5','id':notifyId,'eventDate':eventDate}, function (res){
							//============ Refresh ==============
							$.post('../cases/crm_application.php', {option:'4'},function (res){
								$('.loading').hide();
								$('#UATdetails').show();
								$('#UATdetails').html(res);
							});
							//==================================
			});
		}
		$('#notifyDate').datepicker({
			dateFormat: 'yy-mm-dd'
        });
		</script>";
		break;
	case 5:
		// email Notification sending case

		$qry="SELECT APP_NUMBER, REQUESTOR_UID, CRM_LM1_UID, CRM_REF_NUMBER,APP_TYPE,UAT_DATE FROM CRM_APPLICATION WHERE APP_UID='$appId'";
		$reqInfoQry=executeQuery($qry);
		$reqInfo=$reqInfoQry[1];
		$caseNumber=$reqInfo['APP_NUMBER'];
		$reqId=$reqInfo['REQUESTOR_UID'];
		$requestorName=executeQuery("SELECT full_name FROM SCI_USERTABLE WHERE USR_UID='$reqId'")[1]['full_name'];

		$devIds=executeQuery("SELECT USR_UID FROM CRM_APP_DEVELOPERS WHERE APP_UID='$appId'");
		$devEmails="";
		foreach($devIds[1] as $ids){
			$devEmails=userInfo($ids)['mail'].",".$devEmails;
		}
		$devEmails=rtrim($devEmails,',');
		$appType=$reqInfo['APP_TYPE'];
		$uatDate=$reqInfo['UAT_DATE'];
		$crmLMId=$reqInfo['CRM_LM1_UID'];
		$refNo=$reqInfo['CRM_REF_NUMBER'];
		$reqUser = userInfo($reqId);
		$to = $reqUser['mail'];
		$devUser = userInfo($userId);
		$devEmail=$devUser['mail'];
		$crmLMId=sciGetVariable($appId,'APPLICATION','crmLM1');
		$crmPMInfo=userInfo($crmLMId);
		$crmPmEmail=$crmPMInfo['mail'];
		$cc=$devEmail.",".$devEmails.",".$crmPmEmail;
		$cc=ltrim($cc,',');
		$notificationId=isset($_POST['id'])?$_POST['id']:0;

		switch($notificationId){
			case 12: // UAT DATE EMAIL NOTIFICATION SENT
				$uatDate=isset($_POST['eventDate'])?$_POST['eventDate']:$uatDate;
				$aFields= array('txtRequestorName'=>$requestorName,'requestType'=>$appType,'CaseNumber '=>$caseNumber,'tentativeUATdate'=>substr($uatDate,0,10));
				if($appType=='CR'){
					$emailSubject="SOFTWARE CHANGE REQUEST(CR) UAT DATE OF REQUEST ID ".$caseNumber." ";
					PMFSendMessage($appId,'',$to,$cc,'', $emailSubject, 'tentative_UAT_date_to_requestor.html',$aFields);
				}
				if($appType=='SD'){
					$emailSubject="SOFTWARE DEVELOPMENT REQUEST (SD) UAT DATE OF REQUEST ID ".$caseNumber." ";
					PMFSendMessage($appId,'',$to,$cc,'', $emailSubject, 'tentative_UAT_date_to_requestor.html',$aFields);
				}
				$qry="INSERT INTO CRM_APP_EMAIL_NOTIFICATION (APP_UID,STATUS_ID, EVENT_DATE) VALUES ('$appId', '$notificationId','$uatDate')";
				executeQuery($qry);
				$taskId=3;
				$qry="INSERT INTO CRM_APP_ACTIVITY (APP_UID,USR_UID,TASK_ID,STATUS_ID,PERCENTAGE,COMMENT,UPDATED_DATE) VALUES ('$appId','$userId','$taskId','$notificationId','0.0','Send UAT date',NOW())";
				executeQuery($qry);
				$qry="UPDATE CRM_APPLICATION SET
					CRM_STATUS='2',
					LAST_UPDATED_BY='$userId',
					LAST_UPDATED_DATE=NOW()
					WHERE APP_UID='$appId'";
				executeQuery($qry);
				break;
			case 13: // UAT COLLECTION DATE EMAIL NOTIFICATION SENT
				//$uatCollectionDate= isset($_POST['eventDate'])?$_POST['eventDate']: $uatDate;
				$aFields= array('txtRequestorName'=>$requestorName,'requestType'=>$appType,'CaseNumber '=>$caseNumber);
				if($appType=='CR'){
					$emailSubject="SOFTWARE CHANGE REQUEST(CR) UAT DATE OF REQUEST ID ".$caseNumber." ";
					PMFSendMessage($appId, '', $to,$cc, '', $emailSubject, 'SD_UAT_notification_to_requestor.html',$aFields);
				}
				if($appType=='SD'){
					$emailSubject="SOFTWARE DEVELOPMENT REQUEST (SD) UAT COLLECTION OF REQUEST ID ".$caseNumber." ";
					PMFSendMessage($appId, '', $to,$cc, '', $emailSubject, 'SD_UAT_notification_to_requestor.html');
				}
				$qry="INSERT INTO CRM_APP_EMAIL_NOTIFICATION (APP_UID,STATUS_ID, EVENT_DATE) VALUES ('$appId', '$notificationId','$uatDate')";
				executeQuery($qry);
				$taskId=3;
				$qry="INSERT INTO CRM_APP_ACTIVITY (APP_UID,USR_UID,TASK_ID,STATUS_ID,PERCENTAGE,COMMENT,UPDATED_DATE) VALUES ('$appId','$userId','$taskId','$notificationId','0.0','Send UAT collection notification',NOW())";
				executeQuery($qry);
				$qry="UPDATE CRM_APPLICATION SET
					CRM_STATUS='2',
					LAST_UPDATED_BY='$userId',
					LAST_UPDATED_DATE=NOW()
					WHERE APP_UID='$appId'";
				executeQuery($qry);
				break;
			case 14: // DEPLOYMENT DATE EMAIL NOTIFICATION SENT
				$deploymentDate=isset($_POST['eventDate'])?$_POST['eventDate']:$uatDate;
				$aFields= array('txtRequestorName'=>$requestorName,'requestType'=>$appType,'CaseNumber '=>$caseNumber,'deploymentDate'=>substr($deploymentDate,0,10));
				if($appType=='CR'){
					$emailSubject="SOFTWARE CHANGE REQUEST(CR) UAT COLLECTION OF REQUEST ID ".$caseNumber." ";
					PMFSendMessage($appId, '', $to,$cc, '', $emailSubject, 'SD_deployment_notification_to_requestor.html',$aFields);
				}
				if($appType=='SD'){
					$aFields=array('deploymentDate'=>substr($uatDate,0,10));
					$emailSubject="SOFTWARE DEVELOPMENT REQUEST (SD) DEPLOYMENT NOTIFICATION OF REQUEST ID ".$caseNumber." ";
					PMFSendMessage($appId, '', $to,$cc, '', $emailSubject, 'SD_deployment_notification_to_requestor.html',$aFields);
				}
				$qry="INSERT INTO CRM_APP_EMAIL_NOTIFICATION (APP_UID,STATUS_ID, EVENT_DATE) VALUES ('$appId', '$notificationId','$uatDate')";
				executeQuery($qry);
				$taskId=4;
				$qry="INSERT INTO CRM_APP_ACTIVITY (APP_UID,USR_UID,TASK_ID,STATUS_ID,PERCENTAGE,COMMENT,UPDATED_DATE) VALUES ('$appId','$userId','$taskId','$notificationId','0.0','send deployment date notification',NOW())";
				executeQuery($qry);
				$status=6; // COMPLETED
				$qry="UPDATE CRM_APPLICATION SET
					CRM_STATUS='$status',
					LAST_UPDATED_BY='$userId',
					LAST_UPDATED_DATE=NOW()
					WHERE APP_UID='$appId'";
				executeQuery($qry);
				break;
		}
		break;
	default:
		die("Option not clear... There is something error. Please contact CRM team.");
		break;
}

?>
