<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\TouchSpin;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Marquee';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="marquee-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin([
        'action' => ['update'],
        'layout' => 'horizontal',
        'id' => 'dropdown-marquee-form',
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-1 col-sm-left-align',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-1 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    <input type="hidden" name="hasUpdate" value="true">

    <?= $form->field($model, 'speed')->widget(TouchSpin::classname(), [
        'options' => ['placeholder' => 'Adjust ...'],
        'class' => 'marquee-speed-input',
        'pluginOptions' => [
            'min' => 1,
            'max' => 20,
        ],
    ]) ?>

    

    <?= $form->field($model, 'message')->textarea(['rows' => 5]) ?>

    <?= $form->field($model, 'enabled')->widget(SwitchInput::classname(), [
        'pluginOptions'=>[
            'handleWidth'=>50,
            'size'=>'mini',
            'offColor' => 'danger',
            'onText'=>'Visible',
            'offText'=>'Invisible',
         ],
    ]); ?>

    <div class="form-group">
        <label class="control-label col-sm-1 col-sm-left-align"></label>
        <div class="col-sm-8">
            <?= Html::submitButton($model->isNewRecord ? 'Create Interaction' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm','data-loading-text'=>'Saving...']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
    $this->registerJs("

        $('#dropdown-marquee-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            updateMarquee($(this));
            return false;
        });

    ");
?>



