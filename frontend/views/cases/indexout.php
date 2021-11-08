<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
 use frontend\modules\survey\models\CrmSurveyResponse;

use frontend\models\campaign\Campaign;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' - Cases';
?>
<div class="cases-index">
     <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Outbound Cases</h3></div>
  <div class="panel-body">
   <input type='hidden' id='hdcampainId' value=<?= $campId ?>>
  <?php
       //echo "campId=".$campId;
        Modal::begin([
            'header' => '<h4 id="case-modal-title"></h4>',
            'id' => 'case-modal',
        ]);
        echo '<div id="case-modal-content"></div>';
        Modal::end();
    ?>

 

 
    
    <?php if(Yii::$app->user->can('Create Service Request')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add Case', [
                                        'value'=>Url::to(['cases/create','customer_id' => $model->id,'campaign_id'=>$campId]),
                                        'class' => 'btn btn-xs btn-success console-button', 
                                        'id'=>'create-case-modal-button', 
                                        'data-loading-text'=>'Loading...'
        ]) ?>



    <?php } ?>

    <div style="clear: both;"></div>
    

     <?PHP $form = ActiveForm::begin([
  'action' => ['customer-survey/create?respondent_id=".$model->id."'],'options' => ['method' => 'post'],
      'layout' => 'horizontal',
      'id' => 'customersurvey-form',
      'errorSummaryCssClass' => 'alert alert-danger',
      'fieldConfig' => [
          'template' => '{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}',
          'horizontalCssClasses' => [
              'label' => 'col-sm-3 col-sm-left-align',
              'offset' => 'col-sm-offset-1',
              'wrapper' => 'col-sm-8 error-enabled-custom-field',
              'error' => '',
              'hint' => '',
          ],
      ],
  ]); ?> 



        

<?php ActiveForm::end(); ?>

    <?= Html::hiddenInput('case_to_load', $case_to_load, ['id'=>'case_to_load']) ;?>
     <?= Html::hiddenInput('customer_id', $customer_id, ['id'=>'customer_id1']) ;?>
    <?php \yii\widgets\Pjax::begin(['id' => 'cases','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
    <?= GridView::widget([
        'dataProvider' => $cases,
        'id' => 'cases-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
              'contentOptions' => ['style' => 'width:10px;'],  

              ],
            'case_id',
            'caseStatus.name',
           // 'created_datetime',
            // 'created_by',
            // 'closed_by',
             
            // 'closed_datetime',
            // 'last_modified_datetime',
            // 'case_counter',
            
			[
                'format' => 'raw',
                'value' => function ($model) {                      

                    return '<div class="edit-request-icon"><a data-customer_id="'.$model->customer_id.'" data-id="'.$model->id.'" class="glyphicon glyphicon-menu-hamburger"></a>
                            


                    </div>';
                },
                'label' => 'View Details',
            ],
			
			[
                'format' => 'raw',
                'value' => function ($model) {                      

                    return '<div class="edit-request-icon1">                            

                            <form action="../customer-survey/create?respondent_id='.$model->customer_id.'&case_id='.$model->id.'" method="post">
                            <input type="hidden" name="_csrf" value="ZEZ6Y0xrY3ARGS42fTwhMQgkDgF6BCEGEx4SMXQMBR4CPy0iPCIwNQ==">
                               <button  class="btn btn-basic" action="../customer-survey/create?respondent_id='.$model->customer_id.'&case_id='.$model->id.'"   id="create-customer-survey-modal-button" >Add</button>  

                            </form>


                    </div>';
                },
                'label' => 'Add Customer Survey',
            ],
			[
                'format' => 'raw',
                'value' => function ($model) {                      
                    
                    $inbutton="";
                    $obbutton="";    

                    // if($model->inbound_csat==1){

                        // $inbutton="";

                    // } else if($model->inbound_csat!=1){

                        // $inbutton="none";

                    // }

                    if($model->outbound_csat==1){

                        $obbutton="";

                    }   else                
                    if($model->outbound_csat!=1){

                        $obbutton="none";

                    }



                    return '<div class="view-survey-icon">                            

                                                          
                            <input type="hidden" name="_csrf" value="ZEZ6Y0xrY3ARGS42fTwhMQgkDgF6BCEGEx4SMXQMBR4CPy0iPCIwNQ==">
                               <a style="display:'.$obbutton.' "   class="btn btn-success btn-xs edit-request-icon1 "  data-case_id="'.$model->id.'"  data-survey_id="'.$_GET['campId'].'"   data-customer_id="'.$model->customer_id.'" action=".id="view-customer-survey-modal-button" >Outbound </button>  

                           


                    </div>';
                },
                'label' => 'View Customer CSAT Survey',
            ],
        ],

    ]); ?>
  
  <?php
        Modal::begin([
            'id' => 'customersurvey-modal',
        ]);

        echo '<div id="customersurvey-modal-content"></div>';

        Modal::end();
    ?>

<?php
        

        Modal::begin([
            'id' => 'customersurveyadd-modal',
        ]);

        echo '<div id="customersurveyadd-modal-content"></div>';

        Modal::end();
    ?>
    
     <?php
    $this->registerJs("

        $(document).ready(function(){
            var case_id = $('#case_to_load').val();
            $('#cases-widget tbody tr[data-id='+case_id+']').click();
        });

        // $('#cases').on('pjax:beforeSend', function(event, xhr, settings) {
        //      var url = '".Url::to(['outbound-interaction/loadinteractions'])."?case_id='+ id;
            
        //     $('#interaction-view').load(url);
        // });

        $('#cases-widget tbody tr').click(function (e) {
               
               if($(this).hasClass('active-request-row'))
               return;
            $(this).siblings().removeClass('active-request-row');
            $(this).addClass('active-request-row');
          e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('key');
              var cid=  $('.active-request-row td .edit-request-icon a').data('customer_id');
          
            //alert(id);
                   if(id != undefined) {
                var url = '".Url::to(['outbound-interaction/loadinteractions'])."?case_id='+ id;
                var url2 = '".Url::to(['reservation/loadreservations'])."?case_id='+ id +'&customer_id='+cid;
                reloadInteractions(url);
                reloadReservations(url2);
            }

        });

        /*
        listener to handle click event of edit service request item
        */
        $('#cases-widget tbody .edit-request-icon a').click(function (e) {
            e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('id');
			var hdcampainId=$('#hdcampainId').val();
            var modal_content = $('#case-modal').find('#case-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['cases/view'])."?id=' + id+'&campaign_id='+hdcampainId, function() {
                    $('#case-modal').modal('show');
                });

                
            }
        });






          function reloadReservations(url) {
  $('#create-reservation-modal-button').button('loading');
  Loading('reservations-widget', false);
  $('#reservation-view').load(url);
}






        /*
        listener to handle click event of create request button
        */
        $('#create-case-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var url = '".Url::to(['outbound-interaction/'])."';
            $('#interaction-view').load(url);
            $('#cases-widget tbody tr').removeClass('active-case-row');
            var modal_content = $('#case-modal').find('#case-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#case-modal').modal('show');
                btn.button('reset');
            });
        });



        $('#cases-widget tbody #create-customer-survey-modal-button').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            btn.button('loading');
             var url = '".Url::to(['customer-survey/create'])."';
          //  $('#interaction-view').load(url);
            $('#cases-widget tbody tr').removeClass('active-case-row');
            var modal_content = $('#customersurveyadd-modal').find('#customersurveyadd-modal-content');
            
            modal_content.html('').load( $(this).attr('action'), function() {
             //alert('ZZZZZZZZZZZZZZZZ');
                $('#customersurveyadd-modal').modal('show');
                btn.button('reset');
            });
        });



      
  $('#outboundsurveybutton').click(function (e) {
            e.preventDefault();
            var thisRow = $(this);
			alert('kkkkkkkkkkkkkkkkkkkkkk');
            var crm_survey_response_id =$('#hdcampainId').val();// $(this).data('id');

            var customer_id=$('#customer_id').val();
            var modal_content = $('#customersurvey-modal').find('#customersurvey-modal-content');
            if(crm_survey_response_id != undefined) {
				
                modal_content.html('').load( '".Url::to(['customer-survey/start'])."?crm_survey_response_id=' +crm_survey_response_id+'&customer_id='+customer_id, function() {
                    $('#customersurveyadd-modal').modal('show');
                });


            }
        });

        $('#cases-widget tbody .view-survey-icon .btn').click(function (e) {
            e.preventDefault();
  var thisRow = $(this);

  var survey_id= $(this).data('survey_id'); 
 // var survey_id=$('#hdcampainId').val();

    var caseid = $(this).data('case_id');
    var customer_id=$('.edit-request-icon a ').data('customer_id');
//alert(survey_id);
//alert(caseid);
//alert(survey_id);
//alert('heelooo');
  var data_name =$(this).data('name');
  //alert(caseid);
  var modal_content = $('#customersurvey-modal').find('#customersurvey-modal-content');
  if(survey_id != undefined) {
	  
	  
	
  modal_content.html('').load('".Url::to(['customer-survey/start'])."?crm_survey_response_id=' +survey_id+'&customer_id='+customer_id+'&case_id='+caseid,function(){

    $('#customersurvey-modal').modal('show');

    } );
  }
  });












    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
</div></div>