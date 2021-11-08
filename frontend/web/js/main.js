$(document).ready(function() {


   $(".nav li.disabled a").click(function() {
     return false;
   });


});

function checkStatusFunction(){

     var caseStatus=$('#cases-case_status').val();
       if (caseStatus==1  ||  caseStatus==3 || caseStatus==5 ){
         $('.field-cases-followup_datetime').addClass('required has-error');
         return false;
      }
      else{
         $('.field-cases-followup_datetime').removeClass('required has-error');

      }

}

function saveProduct($form) {
  $btn = $form.find(':submit');
	$btn.button('loading');
	$.ajax({
      url: $form.attr('action'),
      type: 'post',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {
      	$('#product-modal').find('#product-modal-content').html(response.body);
      	if(!response.hasError) {
          $.pjax.reload({container:"#products"});
      	}

      }
    });

	return true;
}
/*
  function to handle the request for creating a new product or updating an exsiting product
*/
function saveComplaint($form) {
  $btn = $form.find(':submit');
	$btn.button('loading');
	$.ajax({
      url: $form.attr('action'),
      type: 'post',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {
      	$('#complaint-modal').find('#complaint-modal-content').html(response.body);
      	if(!response.hasError) {
          $.pjax.reload({container:"#complaint"});
      	}

      }
    });

	return true;
}

/*
  function to handle the request for creating a new service request or updating an exsiting service request
*/


function saveSurvey($form) {
    
  //var formElement = document.querySelector("form");
 // var formData = new FormData(formElement);
  //console.log("in saving case method");
	   //formData.append('cases-myfile', $('input[type=file]')[0].files[0]);
           alert('Survey ready to save');
  $.ajax({
      url: $form.attr('action'),
      type: 'post',
      data:  $('#survey-form').serialize(),
      cache:false,
      processData: false, contentType: false,
      dataType: 'json',
      success: function(response) {
		//console.log("in save case");
                alert(response);
       // $('#customersurvey-modal').find('#customersurvey-modal-content').html(response.body);
        //alert(response);
       //  $('#interaction-modal').find('#interaction-modal-content').html(response.body);
        if(!response.hasError) {
          $.pjax.reload({container:"#cases"});
        }


      }
    });

  return true;
}


function saveCase($form) {
  $btn = $form.find(':submit');
  $btn.button('loading');
  //alert('working..');
  //var formData = new FormData($('#case-form'));
  var formElement = document.querySelector("form");
  var formData = new FormData(formElement);
  console.log("in saving case method");
	   //formData.append('cases-myfile', $('input[type=file]')[0].files[0]);
  $.ajax({
      url: $form.attr('action'),
      type: 'post',
      data: formData,
      cache:false,
      processData: false, contentType: false,
      dataType: 'json',
      success: function(response) {
		console.log("in save case");
        $('#case-modal').find('#case-modal-content').html(response.body);
        //alert(response);
       //  $('#interaction-modal').find('#interaction-modal-content').html(response.body);
        if(!response.hasError) {
			alert('saved');
          $.pjax.reload({container:"#cases"});
        }


      }
    });

  return true;
}
  /* if (jQuery('#cases-myfile')[0].files.length>0){
  jQuery.each(jQuery('#cases-myfile')[0].files,function(i,file){

       formData.append('cases-myfile',file);     // alert(file);
 })
   }*/
  // alert($form.attr('action'));




/*
  function to handle the request for creating a new interaction or updating an exsiting interaction
*/
function saveInteraction($form, $reload_url, case_id) {

  $btn = $form.find(':submit');
  $btn.button('loading');
  console.log("in saving interaction");
  //alert('action='+$form.attr('action')+'reload_url'+$reload_url);
  /*
  var formElement = document.querySelector("form");
  var formData = new FormData(formElement);


	 if (jQuery('#interaction-myfile')[0].files.length>0){
	  jQuery.each(jQuery('#interaction-myfile')[0].files,function(i,file){

		   formData.append('interaction-myfile',file);
		  // alert(file);
	 })
   } */
  $.ajax({
      url: $form.attr('action'),//'../inboundInteraction/create?case_id='+case_id,
      type: 'post',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {
		   // alert(response.body);
			$('#interaction-modal').find('#interaction-modal-content').html(response.body);
			if(!response.hasError) {
				alert("interaction saved");
			   reloadInteractions($reload_url);
			  $('.close').click();
			}
			else{
				if($('#inboundinteraction-channel_type').val()=="")
					  $('.field-inboundinteraction-channel_type').addClass('has-error');
					  if($('#inboundinteraction-interaction_status').val()=="")
					  $('.field-inboundinteraction-interaction_status').addClass('has-error');
					  $btn.button('reset');  
			}
      }
    });

  return true;
}

function saveInteraction2($form, $reload_url, case_id) {
  //alert("entered main");
  $btn = $form.find(':submit');
  $btn.button('loading');
// alert("in save interaction 2 , case_id"+case_id);

 var formElement = document.querySelector("form");
  var formData = new FormData(formElement);

 // alert(jQuery('#interaction-myfile')[0]);
	 if (jQuery('#interaction-myfile')[0].files.length>0){
		jQuery.each(jQuery('#interaction-myfile')[0].files,function(i,file){

		   formData.append('interaction-myfile',file);
		  // alert(file);
	 })
   }

	$.ajax({
	  url:	$form.attr('action'),//'../interaction/create?case_id='+case_id,
      type: 'post',
      data: formData,
      cache:false,
      processData: false, contentType: false,
      dataType: 'json',
      success: function(response) {
		//  alert('AAAAAAAA');
     //   alert("Please create interaction");
	   // location.reload();
      $('#interaction-modal').find('#interaction-modal-content').html(response.body);
        $('#case-modal-title').html('New Interaction');
        if(!response.hasError) {
         // alert($reload_url);
		$.pjax.reload({container:"#interaction-modal-content"});
	    //  reloadInteractions($reload_url);
          $('.close').click();
        }
      }
    });

  return true;
}

function saveSurvey($form, case_id) {

  $btn = $form.find(':submit');
  $btn.button('loading');
 // alert($reload_url + "::"+ case_id);
  $.ajax({
      url: $form.attr('action'),
      type: 'post',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {

   //     $('#survey-modal').modal('show');
      //  $('#survey-modal').find('#survey-modal-content').html(response.body);
      //  $('#survey-modal').find('#survey-modal-title').html('New Interaction');
        //  $('#survey-modal').modal('hide');

		// $('#interaction-modal').find('#interaction-modal-content').html(response.body);
		//alert("Survey details has been saved,Please create interaction for it, if needed.");
		$('#case-modal').find('#case-modal-content').html(response.body);

//		location.reload();

//         $('.close').click();
        if(!response.hasError) {
          $.pjax.reload({container:"#cases"});
		 // $('#interaction-modal').find('#interaction-modal-content').html(response.body);
		  console.log('this is a test');
		//  reloadInteractions($reload_url);
		/*   $.ajax({
                type: "post",
                url: "../frontend/views/interaction/index.php",
                data: 'case_id=' + case_id,
                success: function(response) {
					console.log("data sent successfully");
					$('#create-interaction-modal-button').click();
                }
            });*/
		//	$('#create-interaction-modal-button[data-value=Url::to(["interaction/create","case_id" =>'+case_id+'])]').click();


        }

      }
    });
  return true;
}


/*
  function to handle reloading the interaction list
*/
function reloadInteractions($url) {
  $('#create-interaction-modal-button').button('loading');
  Loading('interactions-widget', false);
  $('#interaction-view').load($url);
}

function reloadCases($url){
	$('#create-case-model-button').button('loading');
	Loading('cases-widget', false);
	$('#cases-index').load($url);
}


/*
  function to handle searching for customers
*/
function searchCustomer($form) {
  /*if(isFormEmpty($form)) {
    $('#search-create-customer').hide();
    $('#searchError ul').html('<li>Enter a search criterion</li>');
    return;
  }*/
  $btn = $form.find(':submit');
  $btn.button('loading');
  Loading('results-widget', false);
  $.ajax({
      url: $form.attr('action'),
      type: 'get',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {
        $('#results_view').html(response.body);
        $btn.button('reset');
        if(response.found) {
          $('#search-create-customer').hide();
        }
        else {
          $('#search-create-customer').show();
        }

      }
    });

  return true;

}

/*
  function to handle searching for service requests
*/
function searchRequests($form) {
  $btn = $form.find(':submit');
  $btn.button('loading');
  Loading('request-list-widget', false);
  $.ajax({
      url: $form.attr('action'),
      type: 'get',
      data: $form.serialize(),
      success: function(response) {
        $('#request-list').html(response);
        $btn.button('reset');
      }
    });

  return true;

}

/*
  function to handle searching for cases
*/
function searchCases($form) {
  $btn = $form.find(':submit');
  $btn.button('loading');
  Loading('case-list-widget', false);
  $.ajax({
      url: $form.attr('action'),
      type: 'get',
      data: $form.serialize(),
      success: function(response) {
        $('#case-list').html(response);
        $btn.button('reset');
      }
    });

  return true;

}


/*
  function to handle filtering dashboard
*/
function searchDashboard($form) {
  $btn = $form.find(':submit');
  $btn.button('loading');
  Loading('dashboard-list-widget',true);
  $.ajax({
      url: $form.attr('action'),
      type: 'get',
      data: $form.serialize(),
      success: function(response) {
        $('#dashboard-list').html(response);
        $btn.button('reset');
      }
    });

  return true;

}

/*
  function to handle filtering dashboard
*/
function generateReport($form) {
	
  $btn = $form.find(':submit');
 // $btn.button('loading');
  Loading('report-list',true);
  
  $.ajax({
      url: $form.attr('action'),
      type: 'get',
      data: $form.serialize(),
      success: function(response) {
        $('#report-generated').show();
        $('#report-list').html(response);
        //$btn.button('reset');
      }
    });

  return true;

}

/*
  function to handle navigating to a customer page through a particular service request
*/
function navigateToCustomer($form) {

  $.ajax({
      url: $form.attr('action')+"?createFromSearch=1",
      type: 'get',
      data: $form.serialize(),
      dataType: 'json',
  });
}


/*
  function to handle lading customer request and its interactions
*/
function loadCustomerRequest($url) {

  $.ajax({
      url: $url,
      type: 'get',
      dataType: 'json',
  });
}


function loadCustomerCase($url) {

  $.ajax({
      url: $url,
      type: 'get',
      dataType: 'json',
  });
}
/*
  function to validate forms which are empty
*/
function isFormEmpty($form) {
  $('.error-summary').hide();
   $empty = true;
  $form.find("input[type=text]").each(function() {
     
      if(this.value) {
          $empty = false;
          return false;
      }
  });
  if($empty) {
    $('.error-summary').show();
  }
  return $empty;
}

function toggleDeleted(state,url) {
	//alert('click');
    $.ajax({
      url: url+"&state="+state,
      type: 'post',
      dataType: 'json',
	  success:function(response){
		    //$(this).prop("disabled",true);
			  //  $(this).attr("disabled","disabled");
            //if(!response.hasError)
		    // alert(response.message);
		  	//else
            // alert('puka');				
			//location.reload();

	  }
  });
}

function toggleDeletedRole(state,url) {
	//alert('click');
    $.ajax({
      url: url+"&state="+state,
      type: 'post',
      dataType: 'json',
	  success:function(response){
		    //$(this).prop("disabled",true);
			  //  $(this).attr("disabled","disabled");
            if(response.hasError){
		     alert(response.message);
			 location.reload();
			}
		  	//else
            // alert('puka');				
			

	  }
  });
}

function addDropdownValue($form, container_id) {
  if(isFormEmpty($form))
    return;
  $btn = $form.find(':submit');
  $btn.button('loading');
  $.ajax({
      url: $form.attr('action'),
      type: 'post',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {
        if(!response.hasError) {
          $.pjax.reload({container:"#"+container_id});
          $btn.button('reset');
          $form.find("input[type=text]").val('');
		  location.reload();
        }  
		else{
			alert(response.error['name']);
    $form.find("input[type=text]").val('');

			// $form.find("input[type=text]").addClass('has-error');

		$btn.button('reset');	
		}
		
      }
  });
}

function updateMarquee($form) {
  $btn = $form.find(':submit');
  $btn.button('loading');
  $.ajax({
      url: $form.attr('action'),
      type: 'post',
      data: $form.serialize(),
      dataType: 'json',
      success: function(response) {
        if(!response.hasError) {
          $.pjax.reload({container:"#marquee"});
          $btn.button('reset');
        }
      }
  });
}

function saveEvents($form, $reload_url,case_id) {

  $btn = $form.find(':submit');
  $btn.button('loading');
//alert("case_id"+case_id);

var formElement = document.querySelector("form");
  var formData = new FormData(formElement);


                $.ajax({
                                //alert("hello");
      url: $form.attr('action'), //' ../interaction/create?case_id='+case_id,
      type: 'post',
      data: formData,
      cache:false,
      processData: false, contentType: false,
      dataType: 'json',
      success: function(response) {
                                   //alert(response.body);
      $('#customerevents-modal').find('#customerevents-modal-content').html(response.body);

      }
    });

  return true;
}

function saveCampaign($form){
  //if(isFormEmpty($form))
    //return;
                $.ajax({
                                //alert("hello am in SaveCampaign");
      url: $form.attr('action'), //' ../interaction/create?case_id='+case_id,
      type: 'post',
      data: $('#campaign-form').serialize(),
      dataType: 'json',
      success: function(response) {
                                   alert(response.body);
                                   console.log("done Adding Campaign");
      }
    });
return true;
}

function doDistribution($form, agentkeys){
	var agentkeysdata = agentkeys;
//	var formserialize = $form.serialize();
  //  formserialize.append('agentkeysdata');
	alert($form.serialize()+"&agent_ids="+agentkeysdata);
	$.ajax({
		url:$form.attr('action'),
		type:'post',
		data: $form.serialize()+"&agent_ids="+agentkeysdata,
		dataType:'json',
		success:function(response){
			console.log("doneeee");
		}
	});

}
