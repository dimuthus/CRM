<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Race';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="race-index">

    <h4><?= $sub_title ?></h4>


    <?php $form = ActiveForm::begin(['id' => 'dropdown-race-form']); ?>
    <table class="dropdown-form">
        <tr>
            <td><?= $form->field($model, 'name')->textInput(['maxlength' => 250, 'placeholder'=>'New Item'])->label(false)->error(false) ?></td>
            <td>
                <input type="hidden" name="hasNew" value="true">
                <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
            </td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>



    <div style="clear: both;"></div>

    <div id="race-widget">
        <?= $this->render(Url::to('race_list'), [
                'dataProvider' => $dataProvider,
        ]) ?>
    </div>

</div>

<?php
    $this->registerJs("

        $('#dropdown-race-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            addDropdownValue($(this),'race');
            return false;
        });

    ");
?>
