<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
 <div class="row">
        <div class="col-md-12"><h4>Summary Report</h4></div>
    </div>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="glyphicon glyphicon-th"></span>
            Report Filter
        </h3>
        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#"></a></div>
    </div>
    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'action' => ['generate-summary'],
                    'method' => 'get',
                    'layout' => 'horizontal',
                    'id' => 'report-filter-form',
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
        ]);
        ?>

        <table id="dashboard-filter-form">
            <tr>
                <td><h5>Filter Report : (By Interaction Creation Date)</h5></td>
                <td>
                    <?=
                    $form->field($searchModel, 'FromDate')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'options' => [
                            'removeButton' => false,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true
                            ]
                        ]
                    ]);
                    ?>
                </td>

                <td>
                    <?=
                    $form->field($searchModel, 'ToDate')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE,
                        'options' => [
                            'removeButton' => false,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true
                            ]
                        ]
                    ]);
                    ?>
                </td>

                <td style="text-align:right">
        <?= Html::submitButton('<span class="glyphicon glyphicon-file"></span> Generate Report', ['class' => 'btn btn-danger btn-sm', 'data-loading-text' => 'Generating...']) ?>
                </td>
            </tr>
        </table>

<?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJs("
    $('#report-filter-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        generateReport($(this));
        return false;
    });

");
?>