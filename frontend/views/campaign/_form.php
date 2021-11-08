<style>
.columns{

	float:left;
}
label{
	width:120px;
}
#chearder{
	text-align:center;
	margin:10px,0,0,10px;
	width:100%;
	height:20px;
	background-color: #ffffcc;
}
</style>
<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;

use frontend\models\customer\AgeGroup;
use frontend\models\customer\Race;
use frontend\models\customer\Source;
use frontend\models\Country;
use frontend\models\City;
use frontend\models\State;
use frontend\models\Events;
use frontend\models\product\Product;
use frontend\models\customer\Smoker;
use frontend\models\customer\Gender;
use frontend\models\customer\CustomerSmokingDetails;



/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\Campaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaign-form">

    <?php $form = ActiveForm::begin([
			//'action' => ['campaign/create'],'options' => ['method' => 'post'],
		  'id' => 'campaign-form',

		'errorSummaryCssClass' => 'alert alert-danger',
	/*	'fieldConfig' => [
		'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
		'horizontalCssClasses' => [
			'label' => 'col-sm-2 col-sm-left-align',
			'offset' => 'col-sm-offset-1',
			'wrapper' => 'col-sm-8 error-enabled-custom-field',
			'error' => '',
			'hint' => '',
			],
		],*/
	]); ?>
     <div class="row">
	<div class="col-sm-4"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-sm-4"> <?= $form->field($model, 'start_date')->widget(DateTimePicker::classname(), [
	'options' => ['placeholder' => 'Select start date time ...'],
	'pluginOptions' => [
		'autoclose' => true
	]
]); ?></div>
       	<div class="col-sm-4"> <?= $form->field($model, 'end_date')->widget(DateTimePicker::classname(), [
	'options' => ['placeholder' => 'Select end date time ...'],
	'pluginOptions' => [
		'autoclose' => true
	]
]); ?></div>

	  </div>
   
 <div class="form-group col-sm-offset-5">
			  <input type="hidden" id="hasCreateCampaign" name="hasCreateCampaign"  value="true">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id'=>'submitButton']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
 $this->registerJs("


    $('#submitButton').click(function (e) {

		var crieteriaserialize =  $($('#campaign-form')[0].elements).not('#campaign-name, #campaign-start_date, #campaign-end_date, #campaign-crieteria,#hasCreateCampaign').serializeArray();
    var crieteriastring = JSON.stringify(crieteriaserialize);
		$('#campaign-crieteria').val(crieteriastring);
		e.preventDefault();
		e.stopImmediatePropagation();

		saveCampaign($(this));
		return false;
    });
");
?>
