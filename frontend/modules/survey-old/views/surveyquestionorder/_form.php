<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurvey;
use frontend\modules\survey\models\CrmSurveyQuestion;

/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestionOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-survey-question-order-form">

    <?php $form = ActiveForm::begin(); ?>
      <?= $form->field($model, 'servey_id')->dropDownList(
            ArrayHelper::map(CrmSurvey::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'name'),
            ['prompt'=>'--- Select Survey Name ---']
        )->label(true);
    ?>
  <?= $form->field($model, 'question_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestion::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'text'),
            ['prompt'=>'--- Select Question Text ---']
        )->label(true);
    ?>
    
    
    
    


    <?= $form->field($model, 'order')->textInput() ?>

    <?= $form->field($model, 'conditional_order_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
