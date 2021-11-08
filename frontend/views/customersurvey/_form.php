<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurvey;
/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyResponse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-survey-response-form">

    <?php $form = ActiveForm::begin(); ?>

 <?= $form->field($model, 'survey_id')->dropDownList(
            ArrayHelper::map(CrmSurvey::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'name'),
            ['prompt'=>'--- Select Survey Name ---']
        )->label('Survey Name');
    ?>


        <div class="form-group" align="center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
