<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Call Status';
/* $this->params['breadcrumbs'][] = $this->title; */
?>
<div class="call_status-index">
    <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body"> 
            <?php $form = ActiveForm::begin(['id' => 'dropdown-interaction-status-form']); ?>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => 250, 'placeholder' => 'New Item'])->label(false)->error(false) ?>
                </div>
                <div class="col-md-1">
                    <input type="hidden" name="hasNew" value="true">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm', 'data-loading-text' => 'Adding...']) ?>                                
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <div id="call_status-widget">
                <?=
                $this->render(Url::to('interaction_status_list'), [
                    'dataProvider' => $dataProvider,
                ])
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("
        $('#dropdown-interaction-status-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            addDropdownValue($(this),'interaction_status');
            return false;
        });

    ");
?>

