<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use frontend\models\cases\ChannelType;
use frontend\models\cases\OutcomeCode1;
use frontend\models\cases\OutcomeCode2;
use frontend\models\cases\OutcomeCode3;
use frontend\models\cases\SeverityLevel;
use frontend\models\cases\CaseStatus;
use frontend\models\campaign\Campaign;
use frontend\modules\tools\models\user\User; 
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\cases\CustomerCases */
/* @var $form ActiveForm */
?>

<div class="case-form">
<?php $form = ActiveForm::begin([
       // 'layout' => 'horizontal',
        'id' => 'case-form',
        'validateOnSubmit' => false,
             'options' => ['enctype'=>'multipart/form-data'],

       // 'errorSummaryCssClass' => 'alert alert-danger',
//        'fieldConfig' => [
//            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
//            'horizontalCssClasses' => [
//                'label' => 'col-sm-4 col-sm-left-align',
//                'offset' => 'col-sm-offset-1',
//                'wrapper' => 'col-sm-8 error-enabled-custom-field',
//                'error' => '',
//                'hint' => '',
//            ],
//        ],
    ]); ?>


          <div class="row">
	  <div class="col-sm-6">
	   <?= $form->field($model, 'product_id')->textInput(['readonly'=> true]) ?>
	 <!-- < ?= $form->field($model, 'campaign')->dropDownList(
            ArrayHelper::map(Campaign::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
         ?> --> 
	</div>
	  <div class="col-sm-6"><?= $form->field($model, 'channel_type')->dropDownList(
            ArrayHelper::map(ChannelType::find()->where('deleted != :id ', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
         ?> </div>
	  </div>

    <div class="row">
	
        
        	  <div class="col-sm-6">
         <?= $form->field($model, 'case_category1')->dropDownList(
            ArrayHelper::map(OutcomeCode1::find()->where('deleted != :id AND id !=1', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
           // ['prompt'=>'-----', 'disabled'=>'disabled']
        ); 
         ?> 
           </div>
  <div class="col-sm-6">
     
           <?= $form->field($model, 'case_category2')->widget(DepDrop::classname(), [
		   		 'data'=> ArrayHelper::map(OutcomeCode2::find()->where('deleted != :id and outcome_code1_id=:outcome_code1_id', ['id'=>1,'outcome_code1_id'=>$model->case_category1])->orderBy('name')->all(), 'id', 'name'),
        'pluginOptions'=>[
            'depends'=>['customercases-case_category1'],
            'placeholder'=>'--- Select Outcome Code 2 ---',
            'url'=>Url::to(['/cases/populateoc2'])
        ]
    ]); ?> 
          </div>
	  </div>

    <div class="row">
	 <div class="col-sm-6">
                      <?= $form->field($model, 'case_category3')->widget(DepDrop::classname(), [
					  		   		    'data'=> ArrayHelper::map(OutcomeCode3::find()->where('deleted != :id and outcome_code2_id=:outcome_code2_id', ['id'=>1,'outcome_code2_id'=>$model->case_category2])->orderBy('name')->all(), 'id', 'name'),

        'pluginOptions'=>[
            'depends'=>['customercases-case_category2'],
            'placeholder'=>'--- Select Outcome Code 3 ---',
            'url'=>Url::to(['/cases/populateoc3'])
        ]
    ]); ?> 
          </div>
        <div class="col-sm-6"><?= $form->field($model, 'case_status')->dropDownList(
            ArrayHelper::map(CaseStatus::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
         ?> </div>
	  </div>

   <div class="row">
	
          <div class="col-sm-6">  <?= $form->field($model, 'call_back_date')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATETIME,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]
    ]);?> </div>
        	  <div class="col-sm-6"><?= $form->field($model, 'escalated_to')->dropDownList(
            ArrayHelper::map(User::find()->where('status_id = :id and role_id != :id', ['id'=>1])->orderBy('username')->all(), 'id', 'username'),
            ['prompt'=>'-----']
        ); 
    ?> </div>

	  </div> 
 <div class="row">
     <div class="col-sm-12"> <?= $form->field($model, 'case_note')->textarea(['readonly'=> true,'rows' => '6'])->label('Note History') ?> </div></div>
    <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'case_note_update')->label('Note') ?> </div>
         
		 <div class="col-sm-6"> <?= $form->field($model, 'severity_level')->dropDownList(
            ArrayHelper::map(SeverityLevel::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?></div>
     </div>

	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'location_of_atm') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'debit_card_number') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'branch_department') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'credit_card_number') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'charge_disputed__note') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'reference_number_of_application') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'application_date') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'new_credit_limit_requested') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'current_credit_limit') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'reason_for_change_in_credit_limit') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'mobile_number_requested_on') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'current_debit_limit') ?> </div>
     </div>
	 
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'new_debit_limit_requested') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'reason_for_change_in_debit_limit') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'charge_disputed') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'product_interest_in') ?> </div>
     </div>
	  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'responsible_officer') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'TAT') ?> </div>
     </div>
	 
	   <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'merchant_number') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'amount') ?> </div>
     </div>
	 
	   <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'mobile_device') ?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'app_version') ?> </div>
     </div>
	 
 <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'email_received_datetime')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATETIME,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]
    ]);?></div>
         <div class="col-sm-6">  <?= $form->field($model, 'email_replied_datetime')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATETIME,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]
    ]);?> </div>
     </div>
  <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'created_datetime')->textInput(['readonly'=> true])?> </div>
         <div class="col-sm-6">  <?= $form->field($model, 'case_field4') ?> </div>
     </div>

 <div class="row">
	  <div class="col-sm-6"> <?= $form->field($model, 'case_field5') ?> </div>
         <div class="col-sm-6"><?=
        $form->field($model, 'myfile[]')->widget(FileInput::classname(), [
        'options' => ['accept' => '*','multiple' => true],
         'pluginOptions' => [
        'showPreview' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false,
        'mainClass' => 'input-group-xs'
    ]
]);
    ?> </div>
     </div>
	
          <div class="form-group">
                       <?= Html::submitButton('Save Case', ['class' => 'btn btn-sm btn-success']) ?>

        </div>
    <?php ActiveForm::end(); ?>

</div><!-- cases-create_form -->
<?php
    $this->registerJs("
        
     	$(document).ready(function () {
        var caseStatus=$('#customercases-case_status').val();
	 if(caseStatus==5){
		$('#customercases-escalated_to').attr('disabled',false);
		}
		else{
		$('#customercases-escalated_to').val('');
		$('#customercases-escalated_to').attr('disabled',true);
		}
            if(caseStatus==3){
         
		$('#customercases-call_back_date-disp').attr('disabled',false);
		}
		else{
		$('#customercases-call_back_date-disp').val('');
			$('#customercases-call_back_date-disp').attr('disabled',true);
		}
	});

	$('#customercases-case_status').change(function(){
               var caseStatus=$('#customercases-case_status').val();
		if(caseStatus==3){

		$('#customercases-call_back_date-disp').attr('disabled',false);
		}
		else{
                                                 

		$('#customercases-call_back_date-disp').val('');
		$('#customercases-call_back_date-disp').attr('disabled',true);
                  //alert('disabled'+caseStatus);
		}
                if(caseStatus==5){
		$('#customercases-escalated_to').attr('disabled',false);
		}
		else{
		$('#customercases-escalated_to').val('');
			$('#customercases-escalated_to').attr('disabled',true);
		}
	   // $('#request-escalated_to').show();
	});
	

         $('#case-form').submit(function (e) {
         e.stopImmediatePropagation();
	saveCase($(this));
        return false;
    });

    ");
?>