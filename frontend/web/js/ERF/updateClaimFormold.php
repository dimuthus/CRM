<style>
.error{
	color:red;
}
</style>
<script>
function myFunctionAmountA(){
    
	var a=$('#expAmountA').val();
	if (a.length==0){
		a=0;
	}
   
    var x = a.replace(/[^0-9\.]/g,'');
    $('#expAmountA').val(x);
    var cleanNum = parseFloat($('#expAmountA').val());
    
    // var totA=num*xx;
    //Jails added...
    var expRate=parseFloat($('#expRateB').val());
    var totA=cleanNum*expRate;
    
    //var d=parseFloat($('#expLessAdvanceD').val());
    
    //var totA_deduction = totA - d;
    
    //$('#expAmountC').val(totA.toFixed(2));
    $('#txtTotalE').val(totA.toFixed(2));
    
   
}



function checkTwoDecimalA(){
	
	var num = parseFloat($('#expAmountA').val());
    var cleanNum = num.toFixed(2);
	$('#expAmountA').val(cleanNum);
    if(num/cleanNum < 1){
		$('#error_expAmountA').html('Please enter only 2 decimal places, we have truncated extra points');
        }
}
function myFunctionRateB(){
	var a=$('#expRateB').val();
	if (a.length==0){
		a=0;
	}
	//var x = a.replace(/[^0-9\.]/g,'');
	var num = parseFloat(a);
    var cleanNum = num.toFixed(4);
    $('#expRateB').val(cleanNum);
	var expAmountA=parseFloat($('#expAmountA').val());
	var totA=expAmountA*cleanNum;
	//$('#expAmountC').val(totA.toFixed(2));
	$('#txtTotalE').val(totA.toFixed(2));
}

function checkFourDecimal(){
	
	var num = parseFloat($('#expRateB').val());
    var cleanNum = num.toFixed(4);
	$('#expRateB').val(cleanNum);
    if(num/cleanNum < 1){
		$('#error_expRateB').html('Please enter only 4 decimal places, we have truncated extra points');
    }
}

function myFunctionLessAdvanceD(){
	var a=$('#expLessAdvanceD').val();
	//alert(a.length);
	if (a.length==0){
		a=0;
	}
	var x = a.replace(/[^0-9\.]/g,'');
	$('#expLessAdvanceD').val(x);
	var c=parseFloat($('#expAmountC').val());
	var d=parseFloat($('#expLessAdvanceD').val());
	var e=c-d;
	$('#txtTotalE').val(e.toFixed(2));
	
}

function checkTwoDecimalD(){
	
	var num = parseFloat($('#expLessAdvanceD').val());
    var cleanNum = num.toFixed(2);
	$('#expLessAdvanceD').val(cleanNum);
    if(num/cleanNum < 1){
		$('#error_expLessAdvanceD').html('Please enter only 2 decimal places, we have truncated extra points');
    }
}
</script>
<?php

   G::LoadClass( 'pmFunctions' );
   //die('ASASASASASASASASAS');
   $appUid  =isset($_SESSION['APPLICATION']) ? $_SESSION['APPLICATION']:0;
   $claimId=$_POST['claimId'];
   
   $qry="SELECT
  `CLIM_ID`,
  `APP_UID`,
  `APP_NUMBER`,
  `EXPENCE_DATE_FROM`,
  `EXPENCE_DATE_TO`,
  `EXPENCE_TYPE`,
  `LOCATION`,
  `DEPARTMENT`,
  `BILLABLE`,
  `DESCRIPTION`,
  `RECEIPT_NO`,
  `CURRENCY`,
  `AMOUNT`,
  `RATE`,
  `TOTAL_AMOUNT`,
  LM_DECISION,
  LM_REMARKS,
  EF_REF_NO,
  EF_DECISION,
  EF_REMARKS,
  EXPENCE_OTHER,
  CLIENT_NAME
FROM `FIN_CLMS` WHERE APP_UID='$appUid' AND CLIM_ID='$claimId' ";

$result=executeQuery($qry);

foreach ($result as $row) {
    
                $expFromDate= $row['EXPENCE_DATE_FROM'];
				$expToDate= $row['EXPENCE_DATE_TO'];
                $expType= $row['EXPENCE_TYPE'];
                $expLocation= $row['LOCATION'];
                $expDepartment= $row['DEPARTMENT'];
				$expBillable= $row['BILLABLE'];
				$clientName=$row['CLIENT_NAME'];
				$expDescription= $row['DESCRIPTION'];
				$expReceiptNo= $row['RECEIPT_NO'];
				$expCurrency= $row['CURRENCY'];
				$expAmountA= $row['AMOUNT'];
				$expRateB= $row['RATE'];
				//$expLessAdvanceD= $row['LESS_ADVANCES'];
				$txtTotalE= $row['TOTAL_AMOUNT'];
		        $lmApproval=$row['LM_DECISION'];
				$expRemarks= $row['LM_REMARKS'];
		        $txtRefNo= $row['EF_REF_NO'];
		        $hdClaimId= $row['CLIM_ID'];
		        $expTypeOther=$row['EXPENCE_OTHER'];
				$efChecked=$row['EF_DECISION'];
				$efComment=$row['EF_REMARKS'];
				$expAmountA=number_format((float)$expAmountA, 2, '.', '');
				$expRateB=number_format((float)$expRateB, 4, '.', '');
				$amountC=$expAmountA*$expRateB;
				$amountC=number_format((float)$amountC, 2, '.', '');
				//$expLessAdvanceD=number_format((float)$expLessAdvanceD, 2, '.', '');
				$txtTotalE=number_format((float)$txtTotalE, 2, '.', ''); 
		
   
       
}
?>

                  

<div style="background-color:#edf7ff; float:left;padding: 15px; margin-top:15px;width:100%;">
<div id="file_form" style="width:49%; float:left;">
<input type='hidden' id='hdClaimId' name='hdClaimId' value=<?php echo $hdClaimId;?>>
   <table id="dialog_table2">
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Expense Date<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:100%; border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;" placeholder='YYYY-MM-DD' type='text' id='expDate' name='expDate'  value="<?php echo $expFromDate; ?>" readonly ><span style="color:red;" id="error_expDate"></span></td>
      </tr>
      
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Expense Type<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'>
            <select name="expType" class="menu1" id="expType"  disabled style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" >
               <option value="">-PLEASE SELECT-</option>
               <?php
                  $SQL    = "SELECT ID,NAME,`DISPLAY_ORDER` FROM FIN_CLM_EXP_TYPE WHERE ISDELETED=0 ORDER BY  `DISPLAY_ORDER` ASC";
                  $result = executeQuery($SQL);
                  
                   foreach ($result as $row) {
                      $id = $row['ID'];
                      $name   = $row['NAME'];
                  ?>
                <option value="<?php  echo $id;?>" <?php echo ($id==$expType)?'selected':'' ?>><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expType" ></span>
         </td>
      </tr>
      
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Expense Type(Others)</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' id='expTypeOther' name='expTypeOther' value="<?php echo $expTypeOther; ?>" disabled  ><span style="color:red;" id="error_expTypeOther" ></span></td>
      </tr>
      
            <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Tax Invoice/ Receipt No</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:100%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' id='expReceiptNo' name='expReceiptNo'  width="100px"  value="<?php echo $expReceiptNo; ?>" disabled  /></td>
      </tr>
      
      </table>
</div>



<div id="file_form" style="width:49%; margin-left:2%; float:left;">
   <table id="dialog_table">
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Location<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'>
            <select style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" name="expLocation" class="menu1" id="expLocation" disabled >
               <option value="">-PLEASE SELECT-</option>
               <?php
                  $SQL    = "SELECT `ID`,`NAME` FROM  `FIN_CLM_EXP_LOCATION` WHERE `ISDELETED` = 0";
                  $result = executeQuery($SQL);
                   foreach ($result as $row) {
                      $id = $row['ID'];
                      $name   = $row['NAME'];
                  ?>
                 <option value="<?php  echo $id;?>"  <?php echo ($id==$expLocation)?'selected':'' ?>><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expLocation" ></span>
         </td>
      </tr>
      
      
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Department/Project<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'>
            <select style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" name="expDepartment" class="menu1" id="expDepartment" disabled  >
               <option value="">-PLEASE SELECT-</option>
               <?php
                  $SQL    = "SELECT id,dept_name FROM SCI_DEPARTMENT WHERE isDeleted=0 ORDER BY  dept_name ASC";
                  $result = executeQuery($SQL);
                   foreach ($result as $row)  {
                      $id = $row['id'];
                      $name   = $row['dept_name'];
                  ?>
                  <option value="<?php  echo $id;?>" <?php echo ($id==$expDepartment)?'selected':'' ?>><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expDepartment" ></span>
         </td>
      </tr>
      
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Billable</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'>
            <label class="radio-inline"><input disabled type="radio" name="expBillable" <?php echo ($expBillable=='1')?'checked':'' ?>   value="1" >YES</label>
            <label class="radio-inline"><input disabled type="radio" name="expBillable" <?php echo ($expBillable=='0')?'checked':'' ?> value="0">NO</label>
         </td>
      </tr>
       <tr id='clientNameId'>
         <td  style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Client Name<span style="color:red;" id="error_expTypeOther" >*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' id='clientName' name='clientName' value='<?php echo $clientName; ?>'   ><span style="color:red;" id="error_clientName" ></span></td>
      </tr>
      
      </table>
</div>



<div id="file_form" style="width:100%; float:left;">
   <table id="dialog_table">
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Description<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;width:100%;padding-left: 15px;'colspan="2"><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;" 
		 type='text' id='expDescription' name='expDescription' value="<?php echo $expDescription; ?>" disabled  /><span style="color:red;" id="error_expDescription" > </span></td>
      </tr>
    </table>
</div>

</div>

<div style="background-color:#edfff9; float:left;padding: 15px; margin-top:15px;width:100%;">

<div id="file_form" style="width:100%;float:left;">
   <table id="dialog_table" style="width:100%;">

      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Currency<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'>
            <select style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" name="expCurrency" class="menu1" id="expCurrency"  >
               <option value="">-PLEASE SELECT-</option>
               <?php
                  $SQL    = "SELECT   `CODE`, CONCAT ( `CODE`,'-',`NAME`) AS c_name FROM FIN_CLM_CURRENCY WHERE `ISDELETED`=0 ORDER BY  `DISPLAY_ORDER` DESC";
                  $result = executeQuery($SQL);
                   foreach ($result as $row)  {
                      $id = $row['CODE'];
                      $name   = $row['c_name'];
                  ?>
                 <option value="<?php  echo $id;?>" <?php echo ($id==$expCurrency)?'selected':'' ?>><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expCurrency" ></span>
         </td>
      </tr>
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Expense Amount<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' placeholder='0.00' id='expAmountA' name='expAmountA'  width="100px" required="true" onkeyup="myFunctionAmountA();" onblur="checkTwoDecimalA();myFunctionAmountA();" value='<?php echo $expAmountA; ?>'><span style="color:green;" id="error_expAmountA" ></span></td>
      </tr>
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Rate<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' id='expRateB' name='expRateB'  width="100px" required="true"  placeholder='1.0000' onblur="checkFourDecimal();myFunctionRateB();" value='<?php echo $expRateB; ?>'><span style="color:green;" id="error_expRateB" ></span></td>
      </tr>
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Total(MYR)</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px;'><input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' id='txtTotalE' name='txtTotalE'  width="100px" readonly value='<?php echo $txtTotalE; ?>' ></td>
      </tr>
    
   </table>
</div>
</div>

<div style="background-color:#edfff9; float:left;padding: 15px; margin-top:15px;width:100%;">			   
<div id="file_form" style="width:49%; margin-left:2%; float:left;">
   <table id="dialog_table">   
			   
			   
			    <tr>
                  <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>Reference No</td>
                  <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 20px;'><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;" type='text' id='txtRefNo' name='txtRefNo' value='<?php echo $txtRefNo; ?>'  width="100px" ></td>
               </tr>
	<div id="file_form" style="width:49%; margin-left:2%; float:left;">
   <table id="dialog_table">
				<tr>
                  <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>EF Action</td>
                  <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 20px;'> 
				  <label  class="radio-inline"><input type="radio" name="efChecked" <?php echo ($efChecked=='1')?'checked':'' ?>   value="1" >YES</label>
				  <label  class="radio-inline"><input type="radio" name="efChecked" <?php echo ($efChecked=='0')?'checked':'' ?>   value="0" >NO</label></td>
               </tr>
			   </table></div>
			  <tr>
                  <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;'>EF Comment<span style="color:red;">*</span></td>
                  <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 20px;'><textarea  required type='text' id='efComment' name='efComment'  width="100px"><?php echo $efComment;?></textarea></td>
               </tr>
   
 </table>
 </div>
 </div>

