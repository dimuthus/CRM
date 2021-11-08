<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model frontend\modules\survey\models\CrmSurvey */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crm-survey-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


              <?= 
                    //$form->field($model, 'max_due_date')->textInput(['maxlength' => 100])
                        $form->field($model, 'opening_date')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]
    ]
    );
                    
                    ?>
             <?= 
                    //$form->field($model, 'max_due_date')->textInput(['maxlength' => 100])
                        $form->field($model, 'closing_date')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]
    ]
    );
                    
                    ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
