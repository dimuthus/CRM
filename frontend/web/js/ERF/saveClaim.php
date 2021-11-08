<?php
G::LoadClass( 'pmFunctions' );
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
$appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;

$status=$_POST['status'];
//var_dump($_POST);
//die('0');
if ($status=='delete'){
	 $claimId=$_POST['claimId'];
	$qryDel="DELETE FROM FIN_CLMS WHERE CLIM_ID='$claimId'";
	executeQuery($qryDel);
	$remainClaimCount="SELECT COUNT(*) AS C_COUNT FROM FIN_CLMS WHERE APP_UID='$appUid'";
	$resRemain=executeQuery($remainClaimCount);
	$resRemainCount=$resRemain[1]['C_COUNT'];
	updateTotal($appUid);
	die($resRemainCount);
}
if (isset($_POST["expFromDate"])){ $expFromDate=mysql_real_escape_string($_POST["expFromDate"]);}
if (isset($_POST["expToDate"])){ $expToDate=mysql_real_escape_string($_POST["expToDate"]);}

if (isset($_POST["expType"])){ $expType=$_POST["expType"];}
if (isset($_POST["expTypeOther"])){ $expTypeOther=mysql_real_escape_string($_POST["expTypeOther"]);}
if (isset($_POST["expLocation"])){$expLocation=$_POST["expLocation"];}
if (isset($_POST["expDepartment"])){ $expDepartment=$_POST["expDepartment"];}
if (isset($_POST["expBillable"])){$expBillable=$_POST["expBillable"];}
if (isset($_POST["expDescription"])){$expDescription=mysql_real_escape_string($_POST["expDescription"]);}
if (isset($_POST["expReceiptNo"])){$expReceiptNo=mysql_real_escape_string($_POST["expReceiptNo"]);}
if (isset($_POST["expCurrency"])){$expCurrency=mysql_real_escape_string($_POST["expCurrency"]);}
if (isset($_POST["expAmountA"])){$expAmountA=mysql_real_escape_string($_POST["expAmountA"]);}
if (isset($_POST["expRateB"])){$expRateB=mysql_real_escape_string($_POST["expRateB"]);}
if (isset($_POST["clientName"])){$clientName=mysql_real_escape_string($_POST["clientName"]);}



if (isset($_POST["hdLessAdvance"])){$hdLessAdvance=mysql_real_escape_string($_POST["hdLessAdvance"]);}
if (isset($_POST["txtTotalE"])){$txtTotalE=mysql_real_escape_string($_POST["txtTotalE"]);}	
$qry="SELECT APP_NUMBER FROM APPLICATION WHERE APP_UID='$appUid'";

$res=executeQuery($qry);
if(count($res)>0)	{
		$app_number=$res[1]['APP_NUMBER'];		
}
if ($status=='create'){
	
	$qryChk="SELECT * FROM  FIN_CLM_APPLICATION  WHERE APP_UID='$appUid'";
	$resChk=executeQuery($qryChk);
	if (count($resChk)){
	$qryUpdate="UPDATE FIN_CLM_APPLICATION SET APP_NUMBER='$app_number',LESS_ADVANCE='$hdLessAdvance' WHERE APP_UID='$appUid'";
	executeQuery($qryUpdate);
	}
	else{
	$qryInsert="INSERT INTO FIN_CLM_APPLICATION (APP_UID,APP_NUMBER,LESS_ADVANCE) VALUES ('$appUid','$app_number','$hdLessAdvance') ";
	executeQuery($qryInsert);	
	}
	//die('puka');
	
	$climSeqRes=executeQuery("SELECT MAX(CLIM_SEQ) AS CLIM_SEQ FROM FIN_CLMS WHERE APP_UID='$appUid'");
	$claimSeq=count($climSeqRes)>0?($climSeqRes[1]['CLIM_SEQ']+1):1;
	
	$query = "INSERT INTO `FIN_CLMS`
            (
             `APP_UID`,
             `APP_NUMBER`,
			 `CLIM_SEQ`,
             `EXPENCE_DATE_FROM`,
			 `EXPENCE_DATE_TO`,
             `EXPENCE_TYPE`,
			 `EXPENCE_OTHER`,
			 `CLIENT_NAME`,
             `LOCATION`,
             `DEPARTMENT`,
             `BILLABLE`,
             `DESCRIPTION`,
             `RECEIPT_NO`,
             `CURRENCY`,
             `AMOUNT`,
             `RATE`,
             `TOTAL_AMOUNT`
             )
VALUES (
        '$appUid',
        '$app_number',
		'$claimSeq',
        '$expFromDate',
		'$expToDate',
        '$expType',
		'$expTypeOther',
		'$clientName',
        '$expLocation',
        '$expDepartment',
        '$expBillable',
        '$expDescription',
        '$expReceiptNo',
        '$expCurrency',
        '$expAmountA',
        '$expRateB',
        '$txtTotalE')";
   executeQuery($query);
   $result = executeQuery("SELECT LAST_INSERT_ID() L");
   $claimId=($result[1]['L']);
   updateTotal($appUid);
   if(isset($_FILES['file_uploads'])){
	//$fileName = $_FILES['file_uploads']['name'];
    $name_array = $_FILES['file_uploads']['name'];
    $tmp_name_array = $_FILES['file_uploads']['tmp_name'];
    $type_array = $_FILES['file_uploads']['type'];
    $size_array = $_FILES['file_uploads']['size'];
    $error_array = $_FILES['file_uploads']['error'];
    for($i = 0; $i < count($tmp_name_array); $i++){
        if(move_uploaded_file($tmp_name_array[$i], "claim_uploads/".$name_array[$i])){
            echo $name_array[$i]." upload is complete<br>";
			$fileName=$name_array[$i];
			$tempFileName=$tmp_name_array[$i];
			$qryR="INSERT INTO `FIN_RECIEPTS`
            (
             `APP_UID`,
             `UPLOADED_DATE`,
             `UPLOADED_BY`,
             `FILE_NAME`,
             `TEMP_NAME`,
             `CLIM_ID`)
			VALUES (
					'$appUid',
					NOW(),
					'$userId',
					'$fileName',
					'$tempFileName',
					'$claimId')";
			executeQuery($qryR);
        } else {
            echo "move_uploaded_file function failed for ".$name_array[$i]."<br>";
        }
    }
  }
}
else if ($status=='update'){
	//var_dump($_POST);
	//die('0');
	$hdClaimId=$_POST['hdClaimId'];
	$additionalUpdate="";
	if (isset($_POST['efChecked'])){
		$expBillable=$_POST["expBillable"];
		$clientName=mysql_real_escape_string($_POST["clientName"]);
		$expCurrency=mysql_real_escape_string($_POST["expCurrency"]);
		$expAmountA=mysql_real_escape_string($_POST["expAmountA"]);
		$expRateB=mysql_real_escape_string($_POST["expRateB"]);
		$txtTotalE=mysql_real_escape_string($_POST["txtTotalE"]);
		$efChecked=$_POST['efChecked'];
		$efComment=mysql_real_escape_string($_POST['efComment']);
		$txtRefNo=mysql_real_escape_string($_POST['txtRefNo']);
		$additionalUpdate=",`CURRENCY`='$expCurrency',`AMOUNT`='$expAmountA',`RATE`='$expRateB',`TOTAL_AMOUNT`='$txtTotalE',BILLABLE=$expBillable,CLIENT_NAME='$clientName' ";
		
	}
	logUpdate($hdClaimId,'beforeUpdate',$userId);
	$qryUpdate="UPDATE `FIN_CLMS`
		SET 
		EF_DECISION='$efChecked',
		EF_REMARKS='$efComment',
		EF_REF_NO='$txtRefNo',
		SF_DECISION='$efChecked',
		CFO_DECISION='$efChecked',
		CEO_DECISION='$efChecked'
		$additionalUpdate
		WHERE `CLIM_ID` = '$hdClaimId' AND APP_UID='$appUid' ";
   //die($qryUpdate);
   executeQuery($qryUpdate);
   	logUpdate($hdClaimId ,'afterUpdate',$userId);

  
   updateTotal($appUid);
   $qry="SELECT
		`FIN_CLMS`.`CURRENCY`   
		, `FIN_CLMS`.`AMOUNT`
		, `FIN_CLMS`.`RATE`
		, `FIN_CLMS`.`LESS_ADVANCES`
		, `FIN_CLMS`.`TOTAL_AMOUNT`
		,if (`FIN_CLMS`.EF_DECISION=1,'YES','NO') AS EF_DECISION
		,`FIN_CLMS`.EF_REMARKS
		,`FIN_CLMS`.EF_REF_NO
		FROM FIN_CLMS WHERE `CLIM_ID` = '$hdClaimId' AND APP_UID='$appUid'";
	$amtRes=executeQuery($qry);
	$amtString="";
	$myObj=  new stdClass();


   
	foreach ($amtRes as $amts){
				//$amtString=$amts['CURRENCY']."@".$amts['AMOUNT']."@".$amts['RATE']."@".$amts['LESS_ADVANCES']."@".$amts['TOTAL_AMOUNT'];
				$AMOUNT=number_format((float)$amts['AMOUNT'], 2, '.', '');
				$RATE=number_format((float)$amts['RATE'], 4, '.', '');
				$LESS_ADVANCES=number_format((float)$amts['LESS_ADVANCES'], 2, '.', '');
				$TOTAL_AMOUNT=number_format((float)$amts['TOTAL_AMOUNT'], 2, '.', '');

				$myObj->CURRENCY=$amts['CURRENCY'];
				$myObj->AMOUNT=$AMOUNT;
				$myObj->RATE=$RATE;
				$myObj->LESS_ADVANCES=$LESS_ADVANCES;
				$myObj->TOTAL_AMOUNT=$TOTAL_AMOUNT;
				$myObj->EF_REF_NO=$amts['EF_REF_NO'];
				$myObj->EF_DECISION=$amts['EF_DECISION'];
				$myObj->EF_REMARKS=$amts['EF_REMARKS'];


	}
			
    $gTotQry="SELECT SUM(TOTAL_AMOUNT) AS GTOT FROM FIN_CLMS WHERE  APP_UID='$appUid' AND EF_DECISION=1";
    $totRes=executeQuery($gTotQry);
	
			$qryLA="SELECT LESS_ADVANCE FROM FIN_CLM_APPLICATION WHERE APP_UID='$appUid' ";
			$resLA=executeQuery($qryLA);
			$LESS_ADVANCES=$resLA[1]['LESS_ADVANCE'];
			$LESS_ADVANCES=number_format((float)$LESS_ADVANCES, 2, '.', '');
			//$overAllTotal=number_format((float)$overAllTotal, 2, '.', '');
			//$overAllTotal=$overAllTotal-$LESS_ADVANCES;
			//$overAllTotal=number_format((float)$overAllTotal, 2, '.', '');
	//$gtot=$totRes[1]['GTOT'];
	$grandTotal=$totRes[1]['GTOT']-$LESS_ADVANCES;
	$myObj->GTOT=number_format((float)$grandTotal, 2, '.', '');
	echo  $myJSON = json_encode($myObj);
}

die();

function updateTotal($appUid){
   $qryTotal=executeQuery("SELECT SUM(TOTAL_AMOUNT) AS TOTAL_AMOUNT FROM FIN_CLMS WHERE APP_UID='$appUid' AND STATUS=1");
   $Tot=$qryTotal[1]['TOTAL_AMOUNT'];
   $qryUpdate2="UPDATE FIN_CLM_APPLICATION SET TOTAL='$Tot' WHERE APP_UID='$appUid'";
   executeQuery($qryUpdate2);
	
}

function logUpdate($cid,$afterOrBefore,$userId){
	$allColumns="`CLIM_ID`,`APP_UID`, `APP_NUMBER`, `CLIM_SEQ`,`EXPENCE_DATE_FROM`,`EXPENCE_DATE_TO`, `EXPENCE_TYPE`,
             `EXPENCE_OTHER`,  `LOCATION`,`DEPARTMENT`,
             `BILLABLE`,
             `DESCRIPTION`,
             `RECEIPT_NO`,
             `CURRENCY`,
             `AMOUNT`,
             `RATE`,
             `LESS_ADVANCES`,
             `TOTAL_AMOUNT`,
             `LM_UID`,
             `LM_DECISION`,
             `LM_REMARKS`,
             `HOD_UID`,
             `HOD_DECISION`,
             `HOD_REMARKS`,
             `EF_UID`,
             `EF_DECISION`,
             `EF_REMARKS`,
             `SF_UID`,
             `SF_DECISION`,
             `SF_REMARKS`,
             `EF_REF_NO`,
             `ATTACHMENT_URL`,
             `ATTACHMENT_NAME`,
             `GRID_ROW`,
             `DRAFT`,
             `CFO_UID`,
             `CFO_DECISION`,
             `CFO_REMARKS`,
             `CEO_UID`,
             `CEO_DECISION`,
             `CEO_REMARKS`,
             `CREATED_DATE`,
             `STATUS`,
             `CLIENT_NAME`";
	$extraColumns=",`LOG_STATUS`,
             `LOG_DATE`,
             `LOG_BY`";
	$insert="INSERT INTO FIN_CLMS_CHANGE_LOG ($allColumns $extraColumns) (SELECT $allColumns, '$afterOrBefore',NOW(),'$userId' FROM FIN_CLMS WHERE CLIM_ID=$cid)";
executeQuery($insert);
	}
?>