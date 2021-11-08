<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Product Replacement Delivery Status';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="replacementdeliverystatus-index">

    <h4><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-replacementdeliverystatus-form']); ?>
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

    <div id="replacementdeliverystatus-widget">
        <?= $this->render(Url::to('replacement_delivery_status_list'), [
                'dataProvider' => $dataProvider,
        ]) ?>
    </div>

</div>

<?php
    $this->registerJs("

        $('#dropdown-replacementdeliverystatus-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            addDropdownValue($(this),'replacementdeliverystatus');
            return false;
        });

    ");
?>
