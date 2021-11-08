<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm; // this doesnt not work....
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use frontend\models\campaign\Campaign;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyResponse */
/* @var $form yii\widgets\ActiveForm */
?>
<h4> Add Campaign-Survey</h4>
<div class="crm-survey-response-form">

    <?php $form = ActiveForm::begin([
  'action' => ['customer-survey/create?respondent_id='.$_GET['respondent_id'].'&case_id='.$_GET['case_id']],'options' => ['method' => 'post'],
      'layout' => 'horizontal',
      'id' => 'customersurvey-form',
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
  ]); ?>
 <?= $form->field($model, 'survey_id')->dropDownList(
            ArrayHelper::map(Campaign::find()->orderBy('id')->all(), 'id', 'name'),
            //['prompt'=>'--- Select Campaign ---']
        )->label('Survey Name');
    ?>


        <div class="form-group" align="center">
        <?= Html::submitButton('Add', ['class' => 'btn btn-success','id'=>'addSurveyBtn']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
 /*
    $('#addSurveyBtn').click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var url=$('#customersurvey-form').attr('action');
	$.post(url, $('#customersurvey-form').serialize(),function (data){
            $('#customersurvey-modal').find('#customersurvey-modal-content').html(data);	});
            return false;
    });
*/
");
?>
