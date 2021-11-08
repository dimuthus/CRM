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
        'action' => ['generate-complete'],
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
<td>
<div class="col-sm-6"><?= 
		//$data=ArrayHelper::map(CaseStatus::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name');
		//print_r($data);
$form->field($searchModel, 'CaseStatus[]')->widget(Select2::classname(), [
    //'data' =>  $data,
	        'data' => ArrayHelper::map(CaseStatus::find()->all(), 'id', 'name'),

    'options' => ['placeholder' => 'Select case status', 'multiple' => true],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [',', ' '],
        'maximumInputLength' => 10
    ],
])->label(false); 
?> 
 </div>
</td>

<td>
<div class="col-sm-6"><?= 
		//$data=ArrayHelper::map(CaseStatus::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name');
		//print_r($data);
$form->field($searchModel, 'createdBy[]')->widget(Select2::classname(), [
    //'data' =>  $data,
	        'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),

    'options' => ['placeholder' => 'Select Agent', 'multiple' => true],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [',', ' '],
        'maximumInputLength' => 10
    ],
])->label(false); 
?> 
 </div>
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
        if($('#reportsearch-fromdate-disp').val()=='' || $('#reportsearch-todate-disp').val()==''){
            alert('Date Field(s) Mandatory');
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