<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

use frontend\modules\tools\models\user\User;
use frontend\models\campaign\Campaign;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-filter">

    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'get',
        'layout' => 'horizontal',
        'id' => 'myinbox-filter-form',
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ]
    ]); ?>

    <table id="myinbox-filter-form">
        <tr>
            <td><h5>Filter Results:</h5></td>
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
                <?= $form->field($searchModel, 'campaignID')->dropDownList(
                        ArrayHelper::map(Campaign::find()->where('deleted = 0')->orderBy('name')->all(), 'id', 'name'),
                        ['prompt'=>'-----']
                    );
                ?>

            </td>

        </tr>
        <tr>
          <td>
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
              <?= $form->field($searchModel, 'CreatedBy')->dropDownList(
                      ArrayHelper::map(User::find()->where('role_id != :role_id AND status_id = :id', ['role_id'=>'Admin','id'=>1])->orderBy('username')->all(), 'id', 'username'),
                      ['prompt'=>'-----']
                  );
              ?>

          </td>

          <td style="text-align:right">
              <?= Html::submitButton('<span class="glyphicon glyphicon-filter"></span> Filter', ['class' => 'btn btn-danger btn-sm','data-loading-text'=>'Filtering...']) ?>
          </td>
        </tr>
    </table>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

    $('#myinbox-filter-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        searchDashboard($(this));
        return false;
    });

");
?>
