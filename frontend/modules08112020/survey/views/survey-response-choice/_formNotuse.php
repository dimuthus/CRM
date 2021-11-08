<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurveyQuestion;
/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyResponseChoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-survey-response-choice-form">

    <?php $form = ActiveForm::begin(); ?>

   <?= $form->field($model, 'question_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestion::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'text'),
            ['prompt'=>'--- Select Question type ---']
        )->label(false);
    ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
