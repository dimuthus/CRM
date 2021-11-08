<script type="text/javascript" src="/lib/pmdynaform/libs/dtjavascript/jquery.validate.js"></script>
<style>
.ui-widget-header {
    border: 1px solid #db1d1d;
    background: #164C82;
    color: #f5eeee;

}
</style>
<script>


function updateClaim(claimId,appNumber,seqNo,stage){

	var rSelection=$("input[name='lmAction_"+claimId+"']:checked").val();
    var approvalCount=0;
	if (rSelection==0){
		//alert(lmComment.length);
		$('#PMcomments_'+claimId).show();

		$('#divPMcomments_'+claimId).dialog({
			 open: function() {
        $('.ui-dialog-titlebar-close').hide();

                                            //added by jalis on 11th July 2018

                                            $('.ui-dialog-title').attr('style','font-size:12px;font-weight:bold;');
                                            $('.ui-dialog-buttonset').attr('style','font-size:12px;font-weight:bold;');

                                            //ends here
   },
				modal: true, title:"Please state the reason to remove the expense claim no("+appNumber+"-"+claimId+"-"+seqNo+").", width: 560,
				buttons: {
							"Save": function() {
							var lmComment=$('#PMremarks_'+claimId).val();
							if (lmComment.length==0){
								$('#error_'+claimId).html("Comment is required");
								return false;
							}
							$.post('../cases/finance/saveClaimApproval.php',{'lmChecked':rSelection,'lmComment':lmComment,'hdClaimId':claimId,'stage':stage},function(res){
								$('.ui-dialog-titlebar-close').click();
								approvalCount=0;
								//alert(res);
								$('#divResult_'+claimId).html('Status:<h style="color:red;">No</h>  Comment:<h style="color:red;">'+lmComment+'<h>');
								//$('#td_OverallTot').html("<span>MYR</span> "+res);
								//getLessAdvanceAndTotal();
								var displayGt="0.00";
								var la="0.00";
								var tot="0.00";
								$.post("../cases/finance/getLessAdvanceAndTotal.php",function (data){
										 var json_obj = JSON.parse(data);//parse JSON
										 la=json_obj.LESS_ADVANCE;
										 tot=json_obj.TOTAL;
										 displayGt=json_obj.GTOTAL;
										 //la=parseFloat(la);
										// la.toFixed(2);
										 $('#form\\[txtLessAdvance\\]').val(la);
										 //displayGt=parseFloat(displayGt);
										 //alert("AAA"+displayGt);
										// displayGt.toFixed(2);

										$('#td_TotalClaim').html("MYR " +tot);
										 $('#td_OverallTot').html("MYR " +displayGt);
								});
							})
							//$('.pmdynaform-label-options').html(lmComment);
							},
		      "Cancel": function() {
			  //$("input[name='lmAction_"+claimId+"']").prop("checked", true);
			  $("input[name='lmAction_"+claimId+"'][value='1']").prop("checked",true);
			  //$('#td_OverallTot').html("<span>MYR</span> "+res);
			  $('#divResult_'+claimId).html('');

              $('.ui-dialog-titlebar-close').click();
        }


		}});

	}
	else{
		approvalCount=approvalCount+1;
		$('#divResult_'+claimId).html('');
		$('#PMremarks').removeAttr("required", "true");
		var lmComment=$('#PMremarks').val();
		$.post('../cases/finance/saveClaimApproval.php',{'lmChecked':rSelection,'lmComment':'','hdClaimId':claimId,'stage':stage},function(res){
			//$('#td_OverallTot').html("<span>MYR</span> "+res);
			getLessAdvanceAndTotal();
			//$('#divResult_'+claimId).html('STATUS-<h style="color:green;">YES</h>');
			//alert(res);
		})
	}
	$('#approvalCount').val(approvalCount);

}


function updateClaimByEf(claimId,appNumber,seqNo){

	$('#clmData').html("");
	$.post('../cases/finance/updateClaimForm.php',{'claimId':claimId}, function(response){
		$('#clmData').html(response);
		//alert(response);
		$('#dvUpdateClm').dialog({
			 open: function() {
		     $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close")
        .removeClass("ui-dialog-titlebar-close")
        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
		 $(this).closest(".ui-dialog")


        //added by jalis on 16th July 2018
        $('.ui-dialog-titlebar-close').attr('style','position: absolute;right: .3em;top: 50%;width: 25px;margin: -10px 0 0 0;padding: 1px;height: 20px;');
        $('.ui-dialog-title').attr('style','font-size:12px;font-weight:bold;');
        $('.ui-dialog-buttonset').attr('style','font-size:12px;font-weight:bold;');

        //ends here

       /* $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close")
        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        $(this).closest(".ui-dialog")
        .find(".ui-icon-closethick").attr('style','background-position: -99px -130px;');
        $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close").attr('style','position: absolute;right: .3em;top: 50%;width: 25px;margin: -10px 0 0 0;padding: 1px;height: 20px;');

         $(this).closest(".ui-dialog").attr('style','font-size:12px;font-weight:bold;');*/


        //$('.ui-dialog-titlebar-close').hide();
        //$('.ui-dialog-title').attr('style','font-size:12px;font-weight:bold; width: 300%;');
   },
			resizable: true,
			position: "center" ,
						  height:700,
						  title:"Expense Claim No("+appNumber+"-"+claimId+"-"+seqNo+")-Item check and update",
						  modal: true,
						  minWidth: 1000,
						  buttons: {
    "Save": function() {
		var expToDate=$('#expToDate').val();
		var expFromDate=$('#expFromDate').val();
		var expType=$('#expType').val();
		var expLocation=$('#expLocation').val();
		var expDepartment=$('#expDepartment').val();
		var expDescription=$('#expDescription').val();
		var expCurrency=$('#expCurrency').val();
		var expAmountA=$('#expAmountA').val();
		var expRateB=$('#expRateB').val();
		//var todayDate=new Date();


	    if (!$("#puka").valid()){
			return false;
		}
	    //alert($("#puka").length);
		var form = $("#puka")[0];
		var form_data = new FormData(form);
        form_data.append("status", 'update');


      $.ajax({
          url:"../cases/finance/saveClaim.php",
            type:'POST',
			async: false,
            dataType: "HTML",
			contentType : false,
            processData : false,
            data:form_data,
			success:function(response){
				//alert(response);
				$('.ui-icon-closethick').click();
				  var json_obj = JSON.parse(response);//parse JSON
				 // alert(json_obj.CURRENCY);
                 $('#td_amountVal_'+claimId).html("<span>"+json_obj.CURRENCY+"</span> "+json_obj.AMOUNT);
				 $('#td_rateVal_'+claimId).text(" "+json_obj.RATE);
			    // $('#td_lessAdvanceVal_'+claimId).html("<span>MYR</span> "+json_obj.LESS_ADVANCES);
				 $('#td_totalVal_'+claimId).html("<span>"+json_obj.CURRENCY+"</span> "+json_obj.TOTAL_AMOUNT);
				 $('#td_OverallTot').html("MYR "+json_obj.GTOT);
				 $('#td_efAction_'+claimId).html(json_obj.EF_DECISION);
				 $('#td_efRemarks_'+claimId).html(json_obj.EF_REMARKS);
				 $('#td_Ref_'+claimId).html(json_obj.EF_REF_NO);
				//$('#claimList').load('../cases/finance/claimList.php');

				//$('#divClmFormUpdate').html("");
				//$('#dvUpdateClm').html("");

				//$('#clmData').html("");
				 // $.post('../cases/finance/claimList.php',{'stage':3,'option':'EF'},function (res){
				 // $('#divClmFormUpdate').html(res);});




            }
        });
    },

    Cancel: function() {
      $( this ).dialog( "close" );
    }
  }});
	})

}
function  getLessAdvanceAndTotal(){
	var displayGt="0.00";
	var la="0.00";
	var tot="0.00";
	$.post("../cases/finance/getLessAdvanceAndTotal.php",function (data){
			 var json_obj = JSON.parse(data);//parse JSON
             la=json_obj.LESS_ADVANCE;
			 tot=json_obj.TOTAL;
			 displayGt=json_obj.GTOTAL;
			 //la=parseFloat(la);
			// la.toFixed(2);
			 $('#form\\[txtLessAdvance\\]').val(la);
			 //displayGt=parseFloat(displayGt);
			 //alert("AAA"+displayGt);
			// displayGt.toFixed(2);

			$('#td_TotalClaim').html("MYR " +tot);
			 $('#td_OverallTot').html("MYR " +displayGt);
	});
}


</script>
<?php
G::LoadClass( 'pmFunctions' );
$userId =isset($_SESSION['USER_LOGGED'])? $_SESSION['USER_LOGGED']:0;
$appId  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
$option =isset($_POST['option']) ? $_POST['option']:0;

$claimRes=claimsList($option);

switch ($option){
	case 'OPS':
	$LESS_ADVANCES="0.000";
	$TOTAL="0.00";
	$GTOTAL="0.00";
		$overAllTotal=0.0;
		$currency="MYR";
		echo "<table width='100%'>";
		foreach ($claimRes as $claimInfo){
			echo "<tr><td>";
			echo createClaimViewOPS($claimInfo,$option);
			echo "</td>";
			echo "<td style=' text-align:center;'></td></tr>";
			$overAllTotal+=$claimInfo['TOTAL_AMOUNT'];

			//$overAllTotal=$overAllTotal-$LESS_ADVANCES;
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

		}
				//--GET LA FORM MAIN TABLE 2018/01/26

			$overAllTotal=$GTOTAL;
			$overAllTotal=number_format((float)$overAllTotal, 2, '.', '');
        $differentiator="----------------";

		echo "</table>";
		if(count($claimRes)>0){
		echo "<table width='100%'>
		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 20%;'>Total Claim Amount</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_TotalClaim' >".$currency." ".$TOTAL."</td><td width='10%'></td></tr>

        <tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 20%;'>Less Advance Taken</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_LessAdvance' >".$currency." ".$LESS_ADVANCES."</td><td width='10%'></td></tr>

        <tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'></td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_differentiator' >".$differentiator."</td><td width='10%'></td></tr>

		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'>Grand Total</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_OverallTot'>".$currency." ".$overAllTotal."</td><td width='10%'><input type='hidden' value=".$overAllTotal." id='txtGTotal'/></td></tr></table>";
		}
		break;
	case 'PM':
	case 'HOD':
	case 'EF':
	case 'SF':
	case 'CFO':
	case 'CEO':
	//die('Case CEO'.$option);
	$total=0.0;
		$overAllTotal=0.0;
		$currency="MYR";
		echo "<table width='100%'>";
		foreach ($claimRes as $claimInfo){
			$refNo="";
			$chkEfRadio="";
			$RadioStrYes="checked='true'";
			$RadioStrNo="";
			echo "<tr><td style='width:95%'>";
			echo createClaimView($claimInfo,$option);
			echo "</td>";
			echo "<td style=' text-align:center; width:5%'>";
			if ($option=='SF'){
			$chkEfRadio=$claimInfo['EF_DECISION'];
			if ($chkEfRadio='yes')
			$RadioStrYes='checked';
		    else
			$RadioStrNo='checked';
			}


			echo "</td></tr>";
			$total+=$claimInfo['TOTAL_AMOUNT'];
			$total=number_format((float)$total, 2, '.', '');
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
		}
		//--GET LA FORM MAIN TABLE 2018/01/26


		echo "</table>";
		if(count($claimRes)>0){
		echo "<table width='100%'>
		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 20%;'>Total Claim Amount</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_TotalClaim' >".$currency." ".$TOTAL."</td><td width='10%'></td></tr>
		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'>Less Advance Taken</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_LessAdvance'>MYR ".$LESS_ADVANCES."</td><td width='10%'><input type='hidden' value='' id='approvalCount' name='approvalCount'></td></tr>

            <tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'></td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_differentiator' >----------------</td><td width='10%'></td></tr>

		<tr><td width='55%'></td><td style='font-size:12px;font-weight:bold; width: 10%;'>Grand Total</td><td style='font-size:12px;font-weight:bold; width: 10%;' id='td_OverallTot'>MYR ".$overAllTotal."</td><td width='10%'><input type='hidden' value='' id='approvalCount' name='approvalCount'></td></tr></table><input type='hidden' value=".$total." id='txtGTotal'/>";
		}
		break;
	default:
	break;
}

function claimsList($option){
	global $appId;
	switch ($option){
		case 'OPS':
		case 'PM':
			$level="AND 1=1";
			break;
		case 'HOD':
			$level="AND FIN_CLMS.LM_DECISION=1";
			break;
		case 'EF':
			$level="AND FIN_CLMS.HOD_DECISION=1";
			break;
		case 'SF':
			$level="AND FIN_CLMS.HOD_DECISION=1";
			break;
		case 'CFO':
			$level="AND FIN_CLMS.SF_DECISION=1";
			break;
		case 'CEO':
			$level="AND FIN_CLMS.CFO_DECISION=1";
			break;
	}
	$claimQry="SELECT
		`UC_Words`(`SCI_DEPARTMENT`.`dept_name`) AS DEPARTMENT		, `FIN_CLMS`.`CLIM_ID`
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
		,  IF(`FIN_CLMS`.`BILLABLE`=1,'Yes','No') AS BILLABLE
		, `FIN_CLMS`.`EXPENCE_DATE_FROM`
		, `FIN_CLMS`.`EXPENCE_DATE_TO`

		, IF(`FIN_CLMS`.EF_DECISION=1,'Yes','No') AS EF_DECISION
		,`FIN_CLMS`.EF_REMARKS
		,`FIN_CLMS`.EF_REF_NO
		,`FIN_CLMS`.CLIENT_NAME
	FROM
		`FIN_CLMS`
		INNER JOIN `FIN_CLM_EXP_LOCATION`
			ON (`FIN_CLMS`.`LOCATION` = `FIN_CLM_EXP_LOCATION`.`ID`)
		INNER JOIN `FIN_CLM_EXP_TYPE`
			ON (`FIN_CLMS`.`EXPENCE_TYPE` = `FIN_CLM_EXP_TYPE`.`ID`)
		INNER JOIN `SCI_DEPARTMENT`
			ON (`FIN_CLMS`.`DEPARTMENT` = `SCI_DEPARTMENT`.`id`)
	 WHERE `FIN_CLMS`.APP_UID='$appId' ".$level."";
	 //die($claimQry);
	$claimRes=executeQuery($claimQry);
	//die($claimQry);
	$fileQry="SELECT FILE_NAME,CLIM_ID FROM FIN_RECIEPTS WHERE APP_UID='$appId'";
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
function createClaimViewOPS($claimInfo,$option){
	$fileName=isset($claimInfo['FILE_NAME'])?$fileName=$claimInfo['FILE_NAME']:'';
	$AMOUNT=number_format((float)$claimInfo['AMOUNT'], 2, '.', '');
	$RATE=number_format((float)$claimInfo['RATE'], 4, '.', '');

	$TOTAL_AMOUNT=number_format((float)$claimInfo['TOTAL_AMOUNT'], 2, '.', '');
	$partialView="";




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
        <th class='tg-yw4l' colspan='9'><div style='font-size:12px;font-weight:bold; width:auto; float:left;'>Claim No (".$claimInfo['APP_NUMBER']."-".$claimInfo['CLIM_ID']."-".$claimInfo['CLIM_SEQ']."):</div>
        <div style='font-size:12px;font-weight:normal; width:auto; float:left;'> ".$claimInfo['DESCRIPTION']."<br></div><div style='float:right;'><input type='button' style='border-radius:15px; font-size: 10px; ' class='myclaims btn btn-danger' id='btnDelete' value='Remove this item' onclick='deleteClaim(".$claimInfo['CLIM_ID'].",".$claimInfo['APP_NUMBER'].",".$claimInfo['CLIM_SEQ'].");return false;'/>
      </div></th>
	    </tr>
	    <tr>
		    <td class='tg-yw4l'>Expense Type<br></td>
        <td>:</td>
		    <td class='tg-txt'>".$claimInfo['EXPENCE_TYPE']."</td>
		    <td class='tg-yw4l'>Project/Department</td>
        <td>:</td>
		    <td class='tg-txt' style='font-size:12px;font-weight:normal;'>".$claimInfo['DEPARTMENT']."<br></td>
		    <td class='tg-yw4l'>Expense Amount</td>
        <td>:</td>
		    <td class='tg-txt' id='td_amountVal_".$claimInfo['CLIM_ID']."'><span>".$claimInfo['CURRENCY']."</span> ".$AMOUNT."</td>
	    </tr>
	    <tr>
		    <td class='tg-yw4l'>Expense Date<br></td>
        <td>:</td>
		    <td class='tg-txt'>".$claimInfo['EXPENCE_DATE_FROM']." To ".$claimInfo['EXPENCE_DATE_TO']."</td>
		    <td class='tg-yw4l'>Billable to Client</td>
        <td>:</td>
		    <td class='tg-txt' style='font-size:12px;font-weight:normal;'>".$claimInfo['BILLABLE']."<br></td>
		    <td class='tg-yw4l'>Conversion Rate</td>
        <td>:</td>
		    <td class='tg-txt' id='td_rateVal_".$claimInfo['CLIM_ID']."'>".$RATE."</td>
	    </tr>
	    <tr>
		    <td class='tg-yw4l'>Location</td>
        <td>:</td>
		    <td class='tg-txt'>".$claimInfo['LOCATION']."</td>
		    <td class='tg-yw4l'>Tax invoice<br></td>
        <td>:</td>
		    <td class='tg-txt'>".$claimInfo['RECEIPT_NO']."</td>
		    <td class='tg-yw4l'>Total Expenses</td>
        <td>:</td>
		    <td class='tg-txt' id='td_totalVal_".$claimInfo['CLIM_ID']."'><span>MYR</span> ".$TOTAL_AMOUNT."</td>
	    </tr>
	    <tr>
		    <td class='tg-yw4l'>Attached Receipts</td>
        <td>:</td>
		    <td class='tg-txt' colspan='4'>";
		      if ($fileName!=""){
		      //var_dump($fileName);
		      foreach($fileName as $file){
			      $data.="<a href='../../../../../claim_uploads/".$file."' target='_blank' >".$file."</a></br>";
		      }
		      }
		      $data.="</td>
		    <td class='tg-yw4l'><br></td>
        <td></td>
		    <td class='tg-txt' id='td_totalVal00_".$claimInfo['CLIM_ID']."'><br></td>
	    </tr>
	  </table></br>";

	return $data;
}


function createClaimView($claimInfo,$option){
	$fileName=isset($claimInfo['FILE_NAME'])?$fileName=$claimInfo['FILE_NAME']:'';
	$AMOUNT=number_format((float)$claimInfo['AMOUNT'], 2, '.', '');
	$RATE=number_format((float)$claimInfo['RATE'], 4, '.', '');
	$LESS_ADVANCES=number_format((float)$claimInfo['LESS_ADVANCES'], 2, '.', '');
	$TOTAL_AMOUNT=number_format((float)$claimInfo['TOTAL_AMOUNT'], 2, '.', '');
	$partialView="";

	$partialViewCurrencyRate="<td class='tg-yw4l' colspan=3></td>";
	if ($claimInfo['CURRENCY']!='MYR'){
		$partialViewCurrencyRate="<td class='tg-yw4l'>Conversion Rate</td><td>:</td><td class='tg-txt' id='td_rateVal_".$claimInfo['CLIM_ID']."'>".$RATE."</td>";
	}
	$efUpdateBtnDisplay="";
	$chkEfRadio="";
	$RadioStrYes="checked='true'";
	$RadioStrNo="";
	if ($option=='SF'){
			$chkEfRadio=$claimInfo['EF_DECISION'];
			if ($chkEfRadio='yes')
			$RadioStrYes='checked';
		    else
			$RadioStrNo='checked';
	}
	$approvalOption="<div style='background: #f5edc4;height: 38px;border-radius: 5px;'>



						<div> <div style='float: left; margin-left: 4px; margin-top: 10px;'>Include this item in the claim?</div><div style='float: right;margin-right: 83%;margin-top: 0px;'><label class='radio-inline'><input name='lmAction_".$claimInfo['CLIM_ID']."' value='1' type='radio' $RadioStrYes onclick='updateClaim(".$claimInfo['CLIM_ID'].",".$claimInfo['APP_NUMBER'].",".$claimInfo['CLIM_SEQ'].",\"$option\")'>Yes
						</label><label class='radio-inline'><input name='lmAction_".$claimInfo['CLIM_ID']."' value='0' type='radio' $RadioStrNo  onclick='updateClaim(".$claimInfo['CLIM_ID'].",".$claimInfo['APP_NUMBER'].",".$claimInfo['CLIM_SEQ'].",\"$option\")'>
							No
						</label></div><div id='divResult_".$claimInfo['CLIM_ID']."' style='float: right;margin-top: -11px;margin-right: 66%;'></div></div>

	</div>
	</br>
	<div style='display:none' id='divPMcomments_".$claimInfo['CLIM_ID']."'><textarea style='width:100%;' id='PMremarks_".$claimInfo['CLIM_ID']."' ></textarea><span style='color:red;' id='error_".$claimInfo['CLIM_ID']."' ></span></div>";
	if ($option=='EF'){
           $efUpdateBtnDisplay="<div style='float:right;'><input style=' clear: both; display: block; margin-bottom: 10px; background-color: #134480; color: white; border: none; padding: 7px;' type='button' id='updateByEf' value='Update' onclick='updateClaimByEf(".$claimInfo['CLIM_ID'].",".$claimInfo['APP_NUMBER'].",".$claimInfo['CLIM_SEQ'].")'></div>";
		   $approvalOption="";
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
        <th class='tg-yw4l' colspan='9'><div style='font-size:12px;font-weight:bold; width:auto; float:left;'>Claim No (".$claimInfo['APP_NUMBER']."-".$claimInfo['CLIM_ID']."-".$claimInfo['CLIM_SEQ']."):</div>
        <div style='font-size:12px;font-weight:normal; width:auto; float:left;'> ".$claimInfo['DESCRIPTION']."<br></div>
		$efUpdateBtnDisplay
          </th>
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Expense Type<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['EXPENCE_TYPE']."</td>
		<td class='tg-yw4l'>Project/Department</td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['DEPARTMENT']."<br></td>
		<td class='tg-yw4l'>Expense Amount</td>
    <td>:</td>
		<td class='tg-txt' id='td_amountVal_".$claimInfo['CLIM_ID']."'><span>".$claimInfo['CURRENCY']."</span> ".$AMOUNT."</td>
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Expense Date<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['EXPENCE_DATE_FROM']." To ".$claimInfo['EXPENCE_DATE_TO']."</td>
		<td class='tg-yw4l'>Billable to Client</td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['BILLABLE']."<br></td>$partialViewCurrencyRate

	  </tr>
	  <tr>
		<td class='tg-yw4l'>Location</td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['LOCATION']."</td>
		<td class='tg-yw4l'>Tax invoice<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['RECEIPT_NO']."</td>
		<td class='tg-yw4l'>Total Expenses</td>
    <td>:</td>
		<td class='tg-txt' id='td_totalVal_".$claimInfo['CLIM_ID']."'><span>MYR</span> ".$TOTAL_AMOUNT."</td>
	  </tr>
	  <tr>
		<td class='tg-yw4l'>Attached Receipts</td>
		<td>:</td>
		<td class='tg-txt' >";
		if ($fileName!=""){
		//var_dump($fileName);
		foreach($fileName as $file){
			$data.="<a href='../../../../../claim_uploads/".$file."' target='_blank' >".$file."</a></br>";
		}
		}
		$data.="</td><td class='tg-yw4l'>Client Name<br></td>
    <td>:</td>
		<td class='tg-txt'>".$claimInfo['CLIENT_NAME']."</td><td class='tg-yw4l'><br></td><td></td><td class='tg-txt' id='td_totalVal00_".$claimInfo['CLIM_ID']."'><br></td>
	  </tr></tr><tr><td colspan='10'>$approvalOption</td></tr><tr><td colspan='10'></td>";
	 //die($option);
	 if ($option=='EF' OR $option=='SF' OR $option=='CFO' ){
	//die("option->".$option);
	$partialView="<tr class='trEfComments' >
		<td class='tg-yw4l' style='background-color:#edfff9'>REF No</td>
    <td style='background-color:#edfff9'>:</td>
		<td class='tg-txt' style='background-color:#edfff9' id='td_Ref_".$claimInfo['CLIM_ID']."' >".$claimInfo['EF_REF_NO']."</td>
		<td class='tg-yw4l' style='background-color:#edfff9'>EF Action<br></td>
    <td style='background-color:#edfff9'>:</td>
		<td class='tg-txt' style='background-color:#edfff9' id='td_efAction_".$claimInfo['CLIM_ID']."'>".$claimInfo['EF_DECISION']."</td>
		<td class='tg-yw4l' style='background-color:#edfff9'>EF Comment</td>
    <td style='background-color:#edfff9'>:</td>
		<td class='tg-txt' style='background-color:#edfff9' id='td_efRemarks_".$claimInfo['CLIM_ID']."'>".$claimInfo['EF_REMARKS']."</td>
	  </tr>";
	}
	else {
		$partialView="";
	}
	$tableEnd="</table>

  </br>";

	return $data.$partialView.$tableEnd;
}
?>
<div id='dvUpdateClm' style="display:none" ><form id='puka'><div id='clmData'></form></div></div>
