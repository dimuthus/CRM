<?php
G::LoadClass( 'pmFunctions' );
$appNumber  =$_POST['appNumber'];//isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
  $qryCountry="SELECT USR_COUNTRY FROM vw_fn_requestor_location WHERE APP_NUMBER='$appNumber'";
  $res=executeQuery($qryCountry);
  $userCountry=$res[1]['USR_COUNTRY'];
  
  $displayCurrency = "MYR";

switch ($userCountry) {
    case "MY":
          $displayCurrency = "MYR";
        break;
    case "LK":
          $displayCurrency = "LKR";
        break;
   
    default:
          $displayCurrency = "MYR";
}
$claimRes=claimsList($appNumber);


		$total=0.0;
		$gTotal=0.0;
		$currency="MYR";
		echo "<table width='100%'>";
		foreach ($claimRes as $claimInfo){
			$refNo="";
			$chkEfRadio="";
			$RadioStrYes="checked='true'";
			$RadioStrNo="";
			echo "<tr><td>";
			echo createClaimView($claimInfo,$displayCurrency);
			echo "</td>";
						
			
			echo "</br>
				<div style='display:none' id='divPMcomments_".$claimInfo['CLIM_ID']."'><textarea style='width:100%;' id='PMremarks_".$claimInfo['CLIM_ID']."' ></textarea><span style='color:red;' id='error_".$claimInfo['CLIM_ID']."' ></span></div>";
				
			
				

			echo "</td></tr>";
			$total+=$claimInfo['TOTAL_AMOUNT'];
			$total=number_format((float)$total, 2, '.', ''); 
		}
		//--GET LA FORM MAIN TABLE 2018/01/26
			$appNumber=$claimInfo['APP_NUMBER'];
			$qryLA="SELECT LESS_ADVANCE,TOTAL,(TOTAL-LESS_ADVANCE) AS GTOTAL FROM FIN_CLM_APPLICATION WHERE APP_NUMBER=$appNumber";
			$resLA=executeQuery($qryLA);
			if (count($resLA)>0){
			$LESS_ADVANCES=$resLA[1]['LESS_ADVANCE'];
		    $TOTAL=$resLA[1]['TOTAL'];
			$GTOTAL=$resLA[1]['GTOTAL'];
			}
		    else
			{
			$LESS_ADVANCES="0.00";
			$TOTAL="0.00";
			$LESS_ADVANCES=number_format((float)$LESS_ADVANCES, 2, '.', '');
			$GTOTAL="0.00";
			}
			//$overAllTotal=number_format((float)$overAllTotal, 2, '.', '');
			$overAllTotal=$GTOTAL;
			$overAllTotal=number_format((float)$overAllTotal, 2, '.', '');
		echo "</table>";
		if(count($claimRes)>0){
		echo "<table width='100%'>
		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 20%;'>Total Claim Amount</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_TotalClaim' >".$displayCurrency." ".$TOTAL."</td><td width='10%'></td></tr>
		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'>Less Advance Taken</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_LessAdvance'>".$displayCurrency." ".$LESS_ADVANCES."</td><td width='10%'><input type='hidden' value='' id='approvalCount' name='approvalCount'></td></tr>
            
            <tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'></td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_differentiator' >----------------</td><td width='10%'></td></tr>
		
		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'>Grand Total</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_OverallTot'>".$displayCurrency." ".$overAllTotal."</td><td width='10%'><input type='hidden' value='' id='approvalCount' name='approvalCount'></td></tr></table><input type='hidden' value=".$total." id='txtGTotal'/>";
		}
		

function claimsList($appNumber){
	$qry="SELECT APP_UID FROM FIN_CLM_APPLICATION WHERE APP_NUMBER='$appNumber'";
	$r=executeQuery($qry);
	$appUid=$r[1]['APP_UID'];
	$claimQry="SELECT
		`SCI_DEPARTMENT`.`dept_name` AS DEPARTMENT
		, `FIN_CLMS`.`CLIM_ID` 
		, `FIN_CLMS`.`APP_NUMBER` 
		, `FIN_CLMS`.`CLIM_SEQ` 
		, `FIN_CLMS`.`DESCRIPTION`
		, `FIN_CLMS`.`AMOUNT`
		, `FIN_CLMS`.`RATE`
		, `FIN_CLMS`.`CURRENCY`
		, `FIN_CLMS`.`RECEIPT_NO`
		, `FIN_CLMS`.`LESS_ADVANCES`
		, `FIN_CLMS`.`TOTAL_AMOUNT`
		, `FIN_CLM_EXP_TYPE`.`NAME` AS EXPENCE_TYPE
		, `FIN_CLM_EXP_LOCATION`.`NAME` AS  LOCATION
		,  IF(`FIN_CLMS`.`BILLABLE`=1,'YES','NO') AS BILLABLE
		,CONCAT (FIN_CLMS.`EXPENCE_DATE_FROM`,' to ',FIN_CLMS.`EXPENCE_DATE_FROM`) AS  EXPENCE_DATE
		, IF(`FIN_CLMS`.EF_DECISION=1,'YES','NO') AS EF_DECISION
		,`FIN_CLMS`.EF_REMARKS
		,`FIN_CLMS`.EF_REF_NO
		, `FIN_CLMS`.`LM_DECISION`
		, `FIN_CLMS`.`HOD_DECISION`
		, `FIN_CLMS`.`SF_DECISION`
		, `FIN_CLMS`.`CFO_DECISION`
		, `FIN_CLMS`.`CEO_DECISION`
		,`FIN_CLMS`.CLIENT_NAME
	FROM
		`FIN_CLMS`
		INNER JOIN `FIN_CLM_EXP_LOCATION` 
			ON (`FIN_CLMS`.`LOCATION` = `FIN_CLM_EXP_LOCATION`.`ID`)
		INNER JOIN `FIN_CLM_EXP_TYPE` 
			ON (`FIN_CLMS`.`EXPENCE_TYPE` = `FIN_CLM_EXP_TYPE`.`ID`)
		INNER JOIN `SCI_DEPARTMENT` 
			ON (`FIN_CLMS`.`DEPARTMENT` = `SCI_DEPARTMENT`.`id`)
	 WHERE `FIN_CLMS`.APP_NUMBER='$appNumber'";
	 //die($claimQry);
	$claimRes=executeQuery($claimQry);
	//die($claimQry);
	$fileQry="SELECT FILE_NAME,CLIM_ID FROM FIN_RECIEPTS WHERE APP_UID='$appUid'";
	$receipts=executeQuery($fileQry);
	
	$i=1;
	foreach ($claimRes as $claim){
		$j=1;
		foreach ($receipts as $rec){
			if($claim['CLIM_ID']==$rec['CLIM_ID']){
				$claimRes[$i]['FILE_NAME'][$j++]=$rec['FILE_NAME'];	
			}
		}
		$i++;
	}
	return $claimRes;
}

function createClaimView($claimInfo,$displayCurrency){
	$fileName=isset($claimInfo['FILE_NAME'])?$fileName=$claimInfo['FILE_NAME']:'';
	$AMOUNT=number_format((float)$claimInfo['AMOUNT'], 2, '.', '');
	$RATE=number_format((float)$claimInfo['RATE'], 4, '.', '');
	$LESS_ADVANCES=number_format((float)$claimInfo['LESS_ADVANCES'], 2, '.', '');
	$TOTAL_AMOUNT=number_format((float)$claimInfo['TOTAL_AMOUNT'], 2, '.', ''); 
	$partialView="";
	$claimStatus="<h style='color:green;'>ACCEPTED</h>";
	
	    if ($claimInfo['LM_DECISION']==0 OR $claimInfo['HOD_DECISION']==0  OR $claimInfo['SF_DECISION']==0 OR $claimInfo['CFO_DECISION']==0 OR $claimInfo['CEO_DECISION']==0){
		$claimStatus="<h style='color:red;'>REJECTED</h>";	
		}
	$partialViewCurrencyRate="<td class='tg-yw4l' colspan=3></td>";
	if ($claimInfo['CURRENCY']!='MYR'){
		$partialViewCurrencyRate="<td class='tg-yw4l'>Conversion Rate</td><td>:</td><td class='tg-txt' id='td_rateVal_".$claimInfo['CLIM_ID']."'>".$RATE."</td>";
	}
	
	
	$data="<style type='text/css'>
	.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
	.tg td{font-family: Arial, sans-serif;
    font-size: 12px;
    padding: 10px 15px;
    border-style: solid;
    border-width: 1px;
    overflow: hidden;
    word-break: normal;
    border-color: #999;
    color: #444;
    background-color: #f8f8f8;
    border: none;}
	.tg th{    font-family: Arial, sans-serif;
    font-size: 12px;
    font-weight: normal;
    padding: 10px 15px;
    border-style: solid;
    border-width: 1px;
    overflow: hidden;
    word-break: normal;
    border-color: #fff;
    color: #090909;
    background-color: #ffffff;
    border-bottom: #164c88 solid 1px;
    
    font-size: 12px;}
	.tg .tg-yw4l{ text-align: left;  }
	.tg .tg-txt{text-align: left; width:20%; font-weight:normal}
	.tar{text-align:right}
	table {border:none !important; border-collapse: collapse;}
	</style></br>
	<table id='".$claimInfo['CLIM_ID']."' class='tg' width='100%' cellspacing='0' cellpadding='0'>
	  <tr>
		<th class='tg-yw4l' colspan='8'>Claim No (".$claimInfo['APP_NUMBER']."-".$claimInfo['CLIM_SEQ']."-".$claimInfo['CLIM_ID'].") ".$claimInfo['DESCRIPTION']."</th><th>(STATUS-".$claimStatus.")<br></th>
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Expense Type<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['EXPENCE_TYPE']."</td>
		<td class='tg-yw4l'>Department</td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['DEPARTMENT']."<br></td>
		<td class='tg-yw4l'>Amount</td>
    <td>:</td>
		<td class='tg-txt' id='td_amountVal_".$claimInfo['CLIM_ID']."'><span>".$claimInfo['CURRENCY']."</span> ".$AMOUNT."</td>
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Expense Date<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['EXPENCE_DATE']."</td>
		<td class='tg-yw4l'>Billable to Client</td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['BILLABLE']."<br></td>
		$partialViewCurrencyRate
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Location</td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['LOCATION']."</td>
		<td class='tg-yw4l'>Tax Invoice<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['RECEIPT_NO']."</td>
		<td class='tg-yw4l'>Total</td>
    <td>:</td>
		<td class='tg-txt' id='td_totalVal_".$claimInfo['CLIM_ID']."'><span>".$displayCurrency."</span> ".$TOTAL_AMOUNT."</td>
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Attachment</td>
    <td>:</td>
		<td class='tg-txt' colspan='0'>";
		if ($fileName!=""){
		//var_dump($fileName);
		foreach($fileName as $file){
			$data.="<a href='../../../../../claim_uploads/".$file."' target='_blank' >".$file."</a></br>";
		}
		}
		$data.="</td><td class='tg-yw4l'>Client Name<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['CLIENT_NAME']."</td><td class='tg-yw4l'><br></td>
    <td></td>
		<td class='tg-txt' id='td_totalVal_".$claimInfo['CLIM_ID']."'><span></span> <br></td>
	  </tr><tr class='trEfComments' >
		<td class='tg-yw4l' style='background-color:#edfff9'>REF NO</td>
    <td style='background-color:#edfff9'>:</td>
		<td class='tg-txt' style='background-color:#edfff9' id='td_Ref_".$claimInfo['CLIM_ID']."' >".$claimInfo['EF_REF_NO']."</td>
		<td class='tg-yw4l' style='background-color:#edfff9'>EF Action<br></td>
    <td style='background-color:#edfff9'>:</td>
		<td class='tg-txt' style='background-color:#edfff9' id='td_efAction_".$claimInfo['CLIM_ID']."'>".$claimInfo['EF_DECISION']."</td>
		<td class='tg-yw4l' style='background-color:#edfff9'>EF Comment</td>
    <td style='background-color:#edfff9'>:</td>
		<td class='tg-txt' style='background-color:#edfff9' id='td_efRemarks_".$claimInfo['CLIM_ID']."'>".$claimInfo['EF_REMARKS']."</td>
	  </tr></table></br>";
	
	return $data;
}
?>
<div id='dvUpdateClm' style="display:none" ><form id='puka'><div id='clmData'></form></div></div>