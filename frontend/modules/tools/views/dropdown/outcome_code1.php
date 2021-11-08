<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Outcome Code 1';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="outcome-code1-index">

   <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">

    <?php $form = ActiveForm::begin(['id' => 'dropdown-outcome-code1-form']); ?>
    <table class="dropdown-form">
        <tr>
            <td><?= $form->field($model, 'name')->textInput(['maxlength' => 250, 'placeholder'=>'New Item'])->label(false)->error(false) ?></td>
			<td><?= $form->field($model, 'inbound')->dropDownList(['1' => 'Inbound', '0' => 'Outbound'])->label(false)->error(false); ?></td>
            <td>
                <input type="hidden" name="hasNew" value="true">
                <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
            </td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>



    <div style="clear: both;"></div>

    <div id="contact-type-widget">
        <?= $this->render(Url::to('outcome_code1_list'), [
                'dataProvider' => $dataProvider,
        ]) ?>
    </div>

</div></div></div></div>


<?php
    $this->registerJs("

        $('#dropdown-outcome-code1-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            addDropdownValue($(this),'outcome-code1');
            return false;
        });

    ");
?>