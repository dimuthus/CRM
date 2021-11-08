<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use frontend\models\cases\CaseStatus;
use frontend\modules\tools\models\user\User; 

		use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-filter">

    <?php $form = ActiveForm::begin([
        'action' => ['generate-customer'],
        'method' => 'get',
        'layout' => 'horizontal',
        'id' => 'report-filter-form',
        'validateOnSubmit' => false,
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

    <table id="dashboard-filter-form">
        <tr>
            <td><h5>Case Creation Date</h5></td>
            <td>
                 <?= $form->field($searchModel, 'FromDate')->input('text', ['maxlength' => 6]); ?> 
            </td>

            <td>
                 <?= $form->field($searchModel, 'ToDate')->input('text', ['maxlength' => 6]); ?> 
            </td>




            <td style="text-align:right">
                <?= Html::submitButton('<span class="glyphicon glyphicon-file"></span> Generate Data', ['class' => 'btn btn-danger btn-sm','data-loading-text'=>'Generating...']) ?>
            </td>
        </tr>
    </table>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("
    $('#report-filter-form').submit(function (e) {
        if($('#reportsearch-fromdate').val()=='' || $('#reportsearch-todate').val()==''){
            alert('Field(s) Mandatory');
            return false;
        }
        else{
            e.preventDefault();
            e.stopImmediatePropagation();
            generateReport($(this));
            return false;
        }
    });
");
?>