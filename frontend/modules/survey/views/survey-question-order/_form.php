<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurvey;
use frontend\models\campaign\Campaign;

use frontend\modules\survey\models\CrmSurveyQuestion;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestionOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-survey-question-order-form">

    <?php $form = ActiveForm::begin(); ?>
      <?= $form->field($model, 'servey_id')->dropDownList(
            ArrayHelper::map(Campaign::find()->where('deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'name'),
            ['prompt'=>'--- Select Survey Name ---']
        )->label(true);
    ?>
  <?= $form->field($model, 'question_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestion::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'text'),
            ['prompt'=>'--- Select Question Text ---']
        )->label(true);
    ?>
    
    
    
    


    <?= $form->field($model, 'order')->textInput() ?>
<?= $form->field($model, 'is_conditional')->inline()->radioList(['1' => 'Yes', '0' => 'No'],['id'=>'myRadio'])->label('Is conditional question?') ?>


      <?= $form->field($model, 'conditional_order_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestion::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'text'),
            ['prompt'=>'--- Select Question Text ---']
        )->label(true);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
   <?php
    $this->registerJs(" 
    $('.field-crmsurveyquestionorder-conditional_order_id').hide();
  //var rval=$('input[name='CrmSurveyQuestionOrder[is_conditional]']:checked').val();
  $(\"input[name='CrmSurveyQuestionOrder[is_conditional]']\").on('click', function() {

    if ($(this).val() == '1') {
        $('.field-crmsurveyquestionorder-conditional_order_id').show();
    }
 if ($(this).val() == '0') {

    $('.field-crmsurveyquestionorder-conditional_order_id').hide();

 }

});

"); ?>
    
</div>
