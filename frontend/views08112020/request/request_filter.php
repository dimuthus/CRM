<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

use frontend\modules\tools\models\user\User;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-filter">

    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'get',
        'layout' => 'horizontal',
        'id' => 'request-filter-form',
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ]
    ]); ?>

    <table id="request-filter-form">
        <tr>
            <td><h5>Filter Service Requests:</h5></td>
            <td>
                <?= $form->field($searchModel, 'FromDate')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE,
                    'options' => [
                        'removeButton' => false,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true
                        ]
                    ]
                ]);?>
            </td>

            <td>
                <?= $form->field($searchModel, 'ToDate')->widget(DateControl::classname(), [
                    'type'=>DateControl::FORMAT_DATE,
                    'options' => [
                        'removeButton' => false,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true
                        ]
                    ]
                ]);?>
            </td>
            <?php if(Yii::$app->user->can('View Service Reuqests Created by All Agents')) { ?>
                <td>
                    <?= $form->field($searchModel, 'created_by')->dropDownList(
                            ArrayHelper::map(User::find()->where('status_id = :id && role_id != :role_id', ['id'=>1,'role_id'=>'Admin'])->orderBy('username')->all(), 'id', 'username'),
                            ['prompt'=>'-----']
                        ); 
                    ?>

                </td>
            <?php } ?>
            <td style="text-align:right">
                <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> Search', ['class' => 'btn btn-danger btn-sm','data-loading-text'=>'Searching...']) ?>
            </td>
        </tr>
    </table>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

    $('#request-filter-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        searchRequests($(this));
        return false;
    });

");
?>