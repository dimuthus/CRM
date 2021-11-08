<style>
  .error{
  color:red;
  }
  .expensedetails{
  background-color: #EDF7FF;
  border-radius: 10px;
  padding: 10px;
  min-height: 310px;
  }
  .expenseamount{
  background-color: #EDF7FF;
  border-radius: 10px;
  padding: 10px;
  margin-left: 3px;
  width: 49%;
  min-height: 200px;

  }
  .expensedescription{
  background-color: #EDF7FF;
  border-radius: 10px;
  padding: 10px;
  margin-left: 3px;
  margin-top: 4px;
  width: 49%;
  min-height: 95px;
  }
  .expensereceipt{
  background-color: #EDF7FF;
  border-radius: 10px;
  padding: 10px;
  }
  .legend {
  border-color: #164C88;
  border-image: none;
  border-style: none none solid;
  border-width: 0 0 2px;
  color: #666666;
  display: block;
  font-family: Arial, sans-serif;
  font-size: 18px;
  width: 100%;
  margin-bottom:10px;
  }

.ui-dialog .ui-dialog-buttonpane {
    text-align: left;
    border-width: 0px 0 0 0;
    background-image: none;
    margin-top: -0.5em;
padding: -0.7em 0em 0.5em -1.6em;
    background-color: #164C82;
}

:not(output):-moz-ui-invalid:not(:focus) {
  box-shadow: none;
}

:not(output):-moz-ui-invalid:-moz-focusring:not(:focus) {
  box-shadow: none;
}

.ui-dialog .ui-dialog-title {
    float: left;
    margin: .1em 0;
    white-space: nowrap;
    width: 95%;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
    float: right;
}

.ui-dialog .ui-dialog-titlebar-close {
font-size:10px;
font-weight:bold;
color: #000000;
position: absolute;right: .3em;
top: 50%;
width: 25px;
margin: -10px 0 0 0;
padding: 2px;
height: 20px;
}

.note{
vertical-align: middle;
font-family: Arial, sans-serif;
font-size: 11px;
color: #145B3D;
}

</style>
<script>

checkMYR();
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

function checkMYR(){
	var currency=$('#expCurrency').val();
	
	if (currency=='MYR'){
		$('#expRateB').val('1.00')
		$('.tr_rate').hide();
	}
	else
		$('.tr_rate').show();

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



<div style="background-color:#ffffff; float:left;padding: 2px; margin-top:15px;width:100%; font-family: Arial, sans-serif;font-size: 14px;">
<div id="file_form" style="width:49%; float:left;"></div>
<input type='hidden' id='hdClaimId' name='hdClaimId' value=<?php echo $hdClaimId;?>>
<div class="row" style="background-color:#FFFFFF; padding:10px;">
    <div class="col-md-6 expensedetails">
      <div class="legend">Expense Details</div>
      <div id="file_form">
        <input type='hidden' id='hdLessAdvance' name='hdLessAdvance' value='0.00'/>
        <table id="dialog_table">
		  <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Expense Type<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <select name="expType" class="menu1" id="expType" disabled style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 12px;" >
                <option value="">-Please Select-</option>
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
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Claim Period From<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:100%; border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" placeholder='YYYY-MM-DD' type='text' id='expFromDate' name='expFromDate' disabled  value="<?php echo $expFromDate; ?>">
                <span style="color:red;" id="error_expFromDate"></span>
              </td>
          </tr>

          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              To<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:100%; border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 12px;" placeholder='YYYY-MM-DD' type='text' id='expToDate' name='expToDate'  disabled  value="<?php echo $expToDate; ?>">
                <span style="color:red;" id="error_expToDate"></span>
              </td>
          </tr>

          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Department/Project<span style="color:red;">*</span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <select style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" name="expDepartment" class="menu1" id="expDepartment" disabled  >
                <option value="">-Please Select-</option>
                <?php
                            $SQL    = "SELECT id,UC_Words(dept_name) AS dept_name FROM SCI_DEPARTMENT WHERE isDeleted=0 ORDER BY  dept_name ASC";
                            $result = executeQuery($SQL);
                             foreach ($result as $row)  {
                                $id = $row['id'];
                                $name   = $row['dept_name'];
                            ?>
                  <option value="<?php  echo $id;?>" <?php echo ($id==$expDepartment)?'selected':'' ?>><?php  echo $name; ?></option>
                </option>
                <?php   } ?>
              </select>
              <span style="color:red;" id="error_expDepartment" ></span>
            </td>
          </tr>

          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Location<span style="color:red;">*</span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <select style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 12px;" name="expLocation" class="menu1" id="expLocation" disabled >
                <option value="">-Please Select-</option>
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

		<tr id='expTypeOtherId'>
            <td  style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Expense Type(Others)<span style="color:red;" id="error_expTypeOther" ></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
              <input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 12px;" type='text' id='expTypeOther' name='expTypeOther' disabled  >
                <span style="color:red;" id="error_expTypeOther" ></span>
            </td>
          </tr>

          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>Tax Invoice/ Receipt No</td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:100%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" type='text' id='expReceiptNo' name='expReceiptNo' disabled width="100px" value="<?php echo $expReceiptNo; ?>"  />
            </td>
          </tr>

          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>Billable to Client</td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <label class="radio-inline">
                <input type="radio" name="expBillable"  value="1" <?php echo ($expBillable=='1')?'checked':'' ?>  >Yes
                   </label>
              <label class="radio-inline">
                <input type="radio" name="expBillable"   value="0" <?php echo ($expBillable=='0')?'checked':'' ?> >No
                   </label>
            </td>
          </tr>
          <tr id='clientNameId'>
            <td  style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Client Name<span style="color:red;" id="error_expTypeOther" ></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" type='text' id='clientName' name='clientName'   value='<?php echo $clientName; ?>'  >
                <span style="color:red;" id="error_clientName" ></span>
              </td>
          </tr>

        </table>
      </div>
    </div>

    <div class="col-md-5 col-md-offset-0 expenseamount">
      <div class="legend">Expense Amount</div>

      <div id="file_form">
        <table id="dialog_table" style="width:100%;">
          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Currency<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <select style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" name="expCurrency" class="menu1" id="expCurrency" onchange="checkMYR()";  >
                <option value="">-Please Select-</option>
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
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Expense Amount<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" type='text' placeholder='0.00' id='expAmountA' name='expAmountA'  width="100px" required="true" onkeyup="myFunctionAmountA();" onblur="checkTwoDecimalA();myFunctionAmountA();" value='<?php echo $expAmountA; ?>'  >
                <span style="color:green;" id="error_expAmountA" ></span>
            </td>
          </tr>
		
          <tr class='tr_rate'>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Conversion Rate<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" type='text' id='expRateB' name='expRateB'  width="100px" required="true"  placeholder='1.0000' onblur="checkFourDecimal();myFunctionRateB();" value='<?php echo $expRateB; ?>'  >
                <span style="color:green;" id="error_expRateB" ></span>
            </td>
          </tr>
	
          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>Total Expenses</td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'>
              <input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" type='text' id='txtTotalE' name='txtTotalE'  width="100px" readonly="" value='<?php echo $txtTotalE; ?>'  >
            </td>
          </tr>
        </table>
      </div>
    </div>

    <div class="col-md-5 col-md-offset-0 expensedescription">
      <div class="legend">Expense Description</div>
      <div id="file_form" style="width:100%; float:left;">
        <table id="dialog_table">
          <tr>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 12px;'>
              Expense Description<span style="color:red;"></span>
            </td>
            <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;width:100%;padding-left: 15px; font-family: Arial, sans-serif;font-size: 12px;'colspan="2">
            <textarea   style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" id='expDescription' name='expDescription'><?php echo $expDescription; ?></textarea>
              <span style="color:red;" id="error_expDescription" > </span>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div class="row" style="background-color:#FFFFFF; padding:10px;">
    <div class="col-sm-12 col-sm-offset-0 expensereceipt">
      <div class="legend">Specialist Finance Review</div>
      <div class="note">
        <font color='red'>*</font>EF = Executive Finance.
      </div>

    <div id="file_form" style="width:100%; margin-left:2%; float:left;font-family: Arial, sans-serif;font-size: 14px;">
   <table class="dialog_table">
			    <tr>
                  <td style='width:15%;padding-bottom: 15px;position: relative;vertical-align:  middle;font-family: Arial, sans-serif;font-size: 12px;'>Reference No</td>
                  <td style='width:35%;padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 20px;font-family: Arial, sans-serif;font-size: 14px;'><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;" type='text' id='txtRefNo' name='txtRefNo' value='<?php echo $txtRefNo; ?>'  width="100px" ></td>

                  <td style='width:10%;padding-bottom: 15px;position: relative;vertical-align:  middle;font-size: 12px'>EF Action</td>
                  <td style='width:40%;padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 20px; font-family: Arial, sans-serif;font-size: 12px;'>
				  <label  class="radio-inline"><input type="radio" name="efChecked" <?php echo ($efChecked=='1')?'checked':'' ?>   value="1" >Yes</label>
				  <label  class="radio-inline"><input type="radio" name="efChecked" <?php echo ($efChecked=='0')?'checked':'' ?>   value="0" >No</label></td>

               </tr>
			    <tr>
                  </table></div>
				  <div style="width:100%;font-family: Arial, sans-serif;font-size: 20px'">
                <font style='padding-left: 15px;font-family: Arial, sans-serif;font-size: 12px'> EF Comment</font><span style="color:red;">*</span></div>
              <div style="width:100%;padding-left: 112px;font-family: Arial, sans-serif;font-size: 12px'">
                 <textarea  required type='text' id='efComment' name='efComment'  style="width:95%;height: 17px;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 12px;"><?php echo $efComment;?></textarea>
              </div>

    </div>
  </div>
