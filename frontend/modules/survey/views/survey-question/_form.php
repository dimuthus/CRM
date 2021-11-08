<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurveyQuestionType;
/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurveyQuestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-survey-question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'question_type_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestionType::find()->where('is_deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select Question type ---']
        )->label(false);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
