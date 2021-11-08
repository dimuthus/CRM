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
use frontend\models\interaction\InteractionStatus;
use frontend\modules\tools\models\user\User; 
use frontend\models\interaction\SeverityLevel;
use frontend\models\cases\ChannelType;

/* @var $this yii\web\View */
/* @var $model frontend\models\interaction\InboundInteraction */
/* @var $form ActiveForm */

 
?>
<div class="inbound-interaction-create_form">
<input type='hidden' id='hdCaseId' value="<?= $model->case_tbl_id; ?>">

   <?php 
   

   $form = ActiveForm::begin([
	'action' => ['inbound-interaction/create?case_id='.$model->case_tbl_id],'options' => ['method' => 'post'],
        //'layout' => 'horizontal',
        'id' => 'interaction-form',
       'validateOnSubmit' => false,
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3 col-sm-left-align',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ],
		'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>

<!--        < ?= $form->field($model, 'case_tbl_id') ?>-->
    
       <div class="row">
	             <div class="col-sm-6">

				 <?= 
				
				 $form->field($model, 'channel_type')->dropDownList(
            ArrayHelper::map(ChannelType::find()->where('deleted != :id AND id !=1', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
         ?> </div>
        <div class="col-sm-6"><?= $form->field($model, 'interaction_status')->dropDownList(
            ArrayHelper::map(InteractionStatus::find()->where('deleted != :id and id!=2', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
         ?> </div>
       
	  </div>
        <div class="row">
	        <div class="col-sm-6"> <?= $form->field($model, 'notes') ?></div>
         </div>
	 
 <div class="row">
	        <div class="col-sm-6"><?= Html::submitButton($model->isNewRecord ? 'Create Interaction' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm','data-loading-text'=>'Saving...']) ?>
</div>
         </div>
    
<!--        < ?= $form->field($model, 'created_by') ?>
        < ?= $form->field($model, 'inbound_interaction_counter') ?>
        < ?= $form->field($model, 'inbound_interaction_id') ?>-->

   
    <?php ActiveForm::end(); ?>

</div><!-- inbound-interaction-create_form -->
<?php
$this->registerJs("

    $('#interaction-form').submit(function (e) {
		e.preventDefault();
        e.stopImmediatePropagation();
        var case_id = $('#hdCaseId').val();
	      var reload_url = '".Url::to(['loadinteractions'])."?case_id='+case_id ;
        saveInteraction($(this),reload_url,case_id);
        return false;
    });

");
?>