<script type="text/javascript" src="/lib/pmdynaform/libs/dtjavascript/jquery.validate.js"></script>
<style>
.error{
	color:red;
}
.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset .orange {
	clear: both;
margin-bottom: 10px;
background-color: #134480;
color: white;
border: none;
padding: 7px;
font-family: Arial, sans-serif;
font-size: 14px;
}
</style>
<script>

$(document).ready(function (){
	$("#btnNext").show();
	$('#dyn_forward').show();

	var cCount=$('.myclaims').length;
	if (cCount==0){
	$("#btnNext").hide();
	$('#dyn_forward').hide();
	}
	
	var todayDate = new Date().getDate();
		var myMinDate=new Date(new Date().setDate(todayDate - 60));
        var myMaxDate=new Date(new Date().setDate(todayDate + 1));
		//alert(todayDate +myMinDate+myMaxDate );
       	$('#expToDate').datetimepicker({ format: "YYYY-MM-DD",minDate:myMinDate,maxDate:myMaxDate }).find("input:seven").prop("disabled", true);;
		$('#expFromDate').datetimepicker({ format: "YYYY-MM-DD",minDate:myMinDate,maxDate:myMaxDate }).find("input:seven").prop("disabled", true);

     var rules = {
         expDescription: {
             required: true
         }
     };
     var messages = {
         expDescription: {
             required: "Please enter name"
         }
     };

 $(document).on('click','#click_link',function(){
	 $('#expReceiptNo').focus();
$('#expTypeOtherId').hide();
$('#clientNameId').hide();
$('#panalClamRequest').show();
$('#expFromDate').val("YYYY-MM-DD");
$('#expToDate').val("  ");


$('#expType').val("");
$('#expLocation').val("");
$('#expDepartment').val("");
$('#expDescription').val("  ");
$('#expCurrency').val("MYR");
$('.tr_rate').hide();

$('#expTypeOther').val("");
$('#expReceiptNo').val("");
$('#expAmountA').val('0.00');
$('#expRateB').val('1.0000');
//$('#expAmountC').val('0.00');
//$('#expLessAdvanceD').val('0.00');
$('#txtTotalE').val('0.00');
$("#dialog_loader").dialog("open");
$('.remove').click();
$("#dialog_loader").css({'display':'show'});
//
return false;
});


$("#dialog_loader").dialog({
   open: function() {
	   var txtLessAdvance=$('#form\\[txtLessAdvance\\]').val();
		//alert(txtLessAdvance);
		$('#hdLessAdvance').val(txtLessAdvance);
        $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close")
        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
		$(this).closest(".ui-dialog")
        .find(".ui-icon-closethick").attr('style','background-position: -99px -130px;');
        $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close").attr('style','position: absolute;right: .3em;top: 50%;width: 25px;margin: -10px 0 0 0;padding: 1px;height: 20px;');
   },
  resizable: true,
  height:630,
  title:"Expense Claim Form", 
  modal: true,
  minWidth: 900,
  autoOpen:false,
  position: 'center top',
    buttons: {
        "1": { id: 'Add', text: 'Add', click: function() {
		$('.ui-dialog-titlebar-close').text("X");
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
		
		//hdLessAdvance
   
	    if (!$("#file_form_ini").valid()){
			return false;
		}
	
		var form = $('#file_form_ini')[0];
		var form_data = new FormData(form);
        var inputs = $(document).find("#file_form_ini input");
       
           $.each($("input[type='file']")[0].files, function(i, file) {
            form_data.append('file', file);
         });
        form_data.append("status", 'create');
        //alert(form_data);		
      $.ajax({
          url:"../cases/finance/saveClaim.php",
            type:'POST',
            dataType: "HTML",
            contentType : false,
            processData : false,
            data:form_data,
            success:function(msg){
				$('.ui-dialog-titlebar-close').click();
				//$( this ).dialog( "close" );
				$('#claimList').load('../cases/finance/claimList.php');
				getLessAdvanceAndTotal();
				$("#btnNext").show();
              
            }
        });
    }, "class": "orange" },
        "2": { id: 'Cancel', text: 'Cancel', click:  function() {
      $( this ).dialog( "close" );
    }, "class": "orange" }
    }
  /*buttons: {
    "Add": function() {
		$('.ui-dialog-titlebar-close').text("X");
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
		
		//hdLessAdvance
   
	    if (!$("#file_form_ini").valid()){
			return false;
		}
	
		var form = $('#file_form_ini')[0];
		var form_data = new FormData(form);
        var inputs = $(document).find("#file_form_ini input");
       
           $.each($("input[type='file']")[0].files, function(i, file) {
            form_data.append('file', file);
         });
        form_data.append("status", 'create');
        //alert(form_data);		
      $.ajax({
          url:"../cases/finance/saveClaim.php",
            type:'POST',
            dataType: "HTML",
            contentType : false,
            processData : false,
            data:form_data,
            success:function(msg){
				$('.ui-dialog-titlebar-close').click();
				//$( this ).dialog( "close" );
				$('#claimList').load('../cases/finance/claimList.php');
				getLessAdvanceAndTotal();
				$("#btnNext").show();
              
            }
        });
    },
    "class": 'cancelButtonClass', 
    Cancel: function() {
      $( this ).dialog( "close" );
    }
  }*/
});


$(document).on('click','.add_more',function(e){
    e.preventDefault();
    
    var filePresent = document.getElementsByClassName('file')[document.getElementsByClassName('file').length-1].files.length;
if(filePresent >0  ){
    $('#extra_file_div').find('.file_div_child').append("<br/><input name='file_uploads[]' type='file' class='multi_files file' /><button class='remove'>X</button>");
    //$(this).before("<div class='file_div_child'><input name='file_uploads[]' type='file' class='multi_files file' /><button class='remove'>X</button></div>");
  }  

});

$(document).on('change','input:file',
        function(){
            $('input:file').removeClass('multi_files');
        if ($(this).val()) {
            if($(this).parent().parent().find('.remove').length <1)
            {
                $(this).after("<button class='remove first_remove' >X</button>");
            }
            $('.add_more').show();
        } 
        else
        {
            $('.add_more').hide();

        }
    });

$(document).on('click','.remove',function(){
 var length = $('#dialog_attachments').find(".file").length; 
if(length > 1 ){
  $(this).prev('input:file').remove();
  $(this).prev('br').remove();
  $(this).remove();

 }
 else
 {
     $(".file").val('');
    $(this).remove();
    $(this).parent('.file_div_child').find('br').remove();
    $('.add_more').hide();
 }

 return false;
 }); 
 });


function deleteClaim(id){
	  
	  $("#dialog-box-delete").dialog({
	  open: function() {
        $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close")
        .html("<span class='ui-button-icon-primary ui-icon ui-icon-closethick'></span>");
        $(this).closest(".ui-dialog")
        .find(".ui-icon-closethick").attr('style','background-position: -99px -130px;');
        $(this).closest(".ui-dialog")
        .find(".ui-dialog-titlebar-close").attr('style','position: absolute;right: .3em;top: 50%;width: 25px;margin: -10px 0 0 0;padding: 1px;height: 20px;');
   },  
	  modal: true,  minWidth: 400,title:"Expense Claim No "+ id,
      buttons : {
        "Confirm" : function() {
          $.post('../cases/finance/saveClaim.php',{'status':'delete','claimId':id},function (dta){
			  $('#clmTr_'+id).remove();
			  $('.ui-dialog-titlebar-close').click();
			  $('#claimList').load('../cases/finance/claimList.php');
			  getLessAdvanceAndTotal();
			  if (dta==0){
				  $('#btnNext').hide();
				  $('#dyn_forward').hide();
			  }
			  else{
				  $('#btnNext').show();
				  $('#dyn_forward').show();
			  }
			
		  });
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });

    $("#dialog-box-delete").dialog("open");
  
}

function claimDetails(id){
	    //$("#dialog_loader").dialog("open");
		$.post('../cases/finance/updateClaimForm.php',{'claimId':id,'stage':0},function(data){
			//alert(data);
			$('#dialog_loaderForFormUpdate').html(data);
			$('#dialog_loaderForFormUpdate').dialog({
				modal: true, title:"Expense Claim Details", width: 700,
				buttons: {
							/*"Update": function() {
							var formUpdate = $('#updateClmForm')[0];
							var form_data = new FormData(formUpdate);
							form_data.append("status", 'update');
							form_data.append("status", 'update');
							alert(form_data);		
							  $.ajax({
								  url:"../cases/finance/saveClaim.php",
									type:'POST',
									dataType: "HTML",
									contentType : false,
									processData : false,
									cache: false,
									data:form_data,
									
									success:function(msg){
										alert(msg);
										$('#claimList').load('../cases/finance/claimList.php');
										$('.ui-dialog-titlebar-close').click();
									   if(msg==1)
									   {
										   alert(123);
									   } 
									}
								});
							},*/
  
				Close: function() {
				$( this ).dialog( "close" );
				}
  }});
			
			});
}

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
	//var num = parseFloat(a);
    //var cleanNum = num.toFixed(4);
    //$('#expRateB').val(cleanNum);
    
    var x = a.replace(/[^0-9\.]/g,'');
    $('#expRateB').val(x);
    var cleanNum = parseFloat($('#expRateB').val());
    
	var expAmountA=parseFloat($('#expAmountA').val());
	var totA=expAmountA*cleanNum;
    
    //jalis added....
    //var d=parseFloat($('#expLessAdvanceD').val());
    //totA_deduction = totA - d;
    
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

/*function myFunctionLessAdvanceD(){
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
	
}*/

/*function checkTwoDecimalD(){
	
	var num = parseFloat($('#expLessAdvanceD').val());
    var cleanNum = num.toFixed(2);
	$('#expLessAdvanceD').val(cleanNum);
    if(num/cleanNum < 1){
		$('#error_expLessAdvanceD').html('Please enter only 2 decimal places, we have truncated extra points');
    }
}*/

 function addNewClaim(){
	  $('#manageClaims').dialog({modal: true, title:"Expense Claim Form", width: 700,});
	
 }	

$("#expType").change(function()
{ 

    var expBillable=$('input[name=expBillable]:checked', '#file_form_ini').val();
	//alert(expBillable);
	if ($("#expType").val()==10){
		$('#expTypeOtherId').show();
		$("#expTypeOther").prop("required", "true");
	}
	else{
		$("#expTypeOther").removeAttr('required');
		$('#expTypeOtherId').hide();
	}
	if ($("#expType").val()==8 || expBillable==1){
		$('#clientNameId').show();
		$("#clientName").prop("required", "true");
	}
	else{
		$("#clientName").removeAttr('required');
		$('#clientNameId').hide();
	}
	
   
})

 $('input[type=radio][name=expBillable]').change(function() {
        if (this.value == 1) {
        $('#clientNameId').show();
		$("#clientName").prop("required", "true");
        }
        else  {
        $("#clientName").removeAttr('required');
		$('#clientNameId').hide();
        }
    });

function  getLessAdvanceAndTotal(){
	var displayGt="0.00";
	$.post("../cases/finance/getLessAdvanceAndTotal.php",function (data){
			 var json_obj = JSON.parse(data);//parse JSON
             var la=json_obj.LESS_ADVANCE;
			 var tot=json_obj.TOTAL;
			 var displayGt=json_obj.GTOTAL;
			// displayGt=parseFloat(tot)-parseFloat(la);
			// la=parseFloat(la);
			// la.toFixed(2);
			 $('#form\\[txtLessAdvance\\]').val(la);
			 //displayGt=parseFloat(displayGt);
			 //alert("AAA"+displayGt);
			// displayGt.toFixed(2);
			 $('#td_OverallTot').html("MYR " +displayGt); 
	});
}

function checkMYR(){
	var cuurency=$('#expCurrency').val();
	//alert(cuurency);
	if ($('#expCurrency').val()=='MYR')
		$('.tr_rate').hide();
	else
		$('.tr_rate').show();
	
}
</script>
<?php

  G::LoadClass( 'pmFunctions' );
  
   ?>

 <input type='hidden' id='stage' name='stage'  width="100px" value=<?php echo $stage=$_POST['stage'];?> >  
 <?php if ($stage==0){	 ?>
 <!--<button type='button' style='padding:4px 10px 4px 10px' class='btn btn-primary click_link'>Add Item</button>-->

 <?php   } ?>
 <div id='claimList'>
 
 <?php
include("claimList.php");

 ?>
 </div>
  <div id="dialog-box-delete" style="display:none;">Are you sure you want to delete this? </div>
 <div id="dialog_loaderForFormUpdate" style="display:none;" ><form id="updateClmForm"></form></div>
<div id="dialog_loader" style="display:none;">


<form id="file_form_ini">

<div style="background-color:#edf7ff; float:left;padding: 15px; margin-top:15px;width:100%; font-family: Arial, sans-serif;font-size: 14px;">
<div id="file_form" style="width:49%; float:left; font-family: Arial, sans-serif;font-size: 14px;">
   <input type='hidden' id='hdLessAdvance' name='hdLessAdvance' value='0.00'/>
   <table id="dialog_table">
    
	  
	   <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Location<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
            <select style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 14px;" name="expLocation" class="menu1" id="expLocation" required >
               <option value="">-Please Select-</option>
               <?php
                  $SQL    = "SELECT `ID`,`NAME` FROM  `FIN_CLM_EXP_LOCATION` WHERE `ISDELETED` = 0";
                  $result = executeQuery($SQL);
                   foreach ($result as $row) {
                      $id = $row['ID'];
                      $name   = $row['NAME'];
                  ?>
               <option value="<?php  echo $id;?>"><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expLocation" ></span>
         </td>
      </tr>
     
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Expense Type<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
            <select name="expType" class="menu1" id="expType" required style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 14px;" >
               <option value="">-Please Select-</option>
               <?php
                  $SQL    = "SELECT ID,NAME,`DISPLAY_ORDER` FROM FIN_CLM_EXP_TYPE WHERE ISDELETED=0 ORDER BY  `DISPLAY_ORDER` ASC";
                  $result = executeQuery($SQL);
                  
                   foreach ($result as $row) {
                      $id = $row['ID'];
                      $name   = $row['NAME'];
                  ?>
               <option value="<?php  echo $id;?>"><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expType" ></span>
         </td>
      </tr>
      
      
      <tr id='expTypeOtherId'>
         <td  style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Expense Type(Others)<span style="color:red;" id="error_expTypeOther" >*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 14px;" type='text' id='expTypeOther' name='expTypeOther'   ><span style="color:red;" id="error_expTypeOther" ></span></td>
      </tr>
      
	   <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Tax Invoice/ Receipt No</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:100%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" type='text' id='expReceiptNo' name='expReceiptNo'  width="100px"  /></td>
      </tr>
      
      </table>
</div>



<div id="file_form" style="width:49%; margin-left:2%; float:left;">
   <table id="dialog_table">  <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>To<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
		 <input style="width:100%; border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent; font-family: Arial, sans-serif;font-size: 14px;" placeholder='YYYY-MM-DD' type='text' id='expToDate' name='expToDate'  required><span style="color:red;" id="error_expToDate"></span>
		 </td>
      </tr>
       
       <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Department/Project<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
            <select style="width:100%; border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" name="expDepartment" class="menu1" id="expDepartment" required  >
               <option value="">-Please Select-</option>
               <?php
                  $SQL    = "SELECT id,UC_Words(dept_name) AS dept_name FROM SCI_DEPARTMENT WHERE isDeleted=0 ORDER BY  dept_name ASC";
                  $result = executeQuery($SQL);
                   foreach ($result as $row)  {
                      $id = $row['id'];
                      $name   = $row['dept_name'];
                  ?>
               <option value="<?php  echo $id;?>"><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expDepartment" ></span>
         </td>
      </tr>
      
      
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Billable to Client</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
            <label class="radio-inline"><input type="radio" name="expBillable" value="1" >Yes</label>
            <label class="radio-inline"><input type="radio" name="expBillable" value="0" checked="true">No</label>
         </td>
      </tr>
       <tr id='clientNameId'>
         <td  style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Client Name<span style="color:red;" id="error_expTypeOther" >*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" type='text' id='clientName' name='clientName'   ><span style="color:red;" id="error_clientName" ></span></td>
      </tr>
      
      </table>
</div>



<div id="file_form" style="width:100%; float:left;">
   <table id="dialog_table">
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Expense Description<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;width:100%;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'colspan="2"><input style="width:100%;border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;"  type='text' id='expDescription' name='expDescription' required /> </span></td>
      </tr>
    </table>
</div>

</div>

<div style="background-color:#edf7ff; float:left;padding: 15px; margin-top:15px;width:100%;">

<div id="file_form" style="width:100%;float:left;">
   <table id="dialog_table" style="width:100%;">

      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Currency<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'>
            <select style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" name="expCurrency" class="menu1" id="expCurrency" onchange="checkMYR()"; required >
               <option value="">-Please Select-</option>
               <?php
                  $SQL    = "SELECT   `CODE`, CONCAT ( `CODE`,'-',`NAME`) AS c_name FROM FIN_CLM_CURRENCY WHERE `ISDELETED`=0 ORDER BY  `DISPLAY_ORDER` DESC";
                  $result = executeQuery($SQL);
                   foreach ($result as $row)  {
                      $id = $row['CODE'];
                      $name   = $row['c_name'];
                  ?>
               <option value="<?php  echo $id;?>" <?php echo ($id=="MYR")? 'selected':'' ?>><?php  echo $name; ?></option>
               <?php   } ?>
            </select>
            <span style="color:red;" id="error_expCurrency" ></span>
         </td>
      </tr>
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Expense Amount<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" type='text' placeholder='0.00' id='expAmountA' name='expAmountA'  width="100px" required="true" onkeyup="myFunctionAmountA();" onblur="checkTwoDecimalA();myFunctionAmountA();" ><span style="color:green;" id="error_expAmountA" ></span></td>
      </tr>
	  
	   <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Claim Period From<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:100%; border: none; border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" placeholder='YYYY-MM-DD' type='text' id='expFromDate' name='expFromDate'  required><span style="color:red;" id="error_expFromDate"></span>
		 </td>
      </tr>
      <tr class='tr_rate'>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Conversion Rate<span style="color:red;">*</span></td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" type='text' id='expRateB' name='expRateB'  width="100px" required="true"  placeholder='1.0000' onblur="checkFourDecimal();myFunctionRateB();" ><span style="color:green;" id="error_expRateB" ></span></td>
      </tr>
      <tr>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle; font-family: Arial, sans-serif;font-size: 14px;'>Total Expenses</td>
         <td style='padding-bottom: 15px;position: relative;vertical-align:  middle;padding-left: 15px; font-family: Arial, sans-serif;font-size: 14px;'><input style="width:90%;border: none;border-bottom: #d1d1d1 solid 1px;background-color: transparent;font-family: Arial, sans-serif;font-size: 14px;" type='text' id='txtTotalE' name='txtTotalE'  width="100px" readonly  ></td>
      </tr>
    
   </table>
</div>
</div>
  
  
 <div style="background-color:#edf7ff; float:left;padding: 15px; margin-top:15px;width:100%; font-family: Arial, sans-serif;font-size: 14px;">
 <div id="file_form" style="width:100%;float:left;">
   <table id="dialog_table" style="width:100%;"> 
     <tr id="dialog_attachments">
         <td>Click on browse to upload Receipts</td>
         <td>
            <div id="extra_file_div">
               <div class="file_div_child" ><input name="file_uploads[]" type="file" class="multi_files file" /></div>
            </div>
         </td>
         <td>
            <button class="add_more" style=" clear: both; display: block; margin-bottom: 10px; background-color: #134480; color: white; border: none; padding: 7px; font-family: Arial, sans-serif;font-size: 14px;">Add more receipt</button>
         </td>
      </tr>
      
   </table>
</div>

</div> 
 </form>  
  <?php die ();?>
