<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

use frontend\models\request\RequestType;
use frontend\models\request\RequestDetailType;
use frontend\models\request\RequestAdditionalType;
use frontend\models\request\RequestSupplementalType;
use frontend\models\request\RequestStatus;
use frontend\models\request\RequestPriority;
use frontend\models\Country;
use frontend\models\product\Product;
use frontend\modules\tools\models\user\User;
use frontend\models\request\RequestServiceCenter;
use frontend\models\request\RequestDevice;
use frontend\models\request\RequestEnhancement;
use frontend\models\request\RequestSoftware;

/* @var $this yii\web\View */
/* @var $model frontend\models\request\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'request-form',
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-4 col-sm-left-align',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    Fields with <span style="color:red">*</span> are mandatory.<br/><br/>
    
    <?= $form->field($model, 'service_request_type')->dropDownList(
            ArrayHelper::map(RequestType::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>
    

    <?= $form->field($model, 'detail_type')->widget(DepDrop::classname(), [
        'data'=> ArrayHelper::map(RequestDetailType::find()->where('deleted != :id and type_id=:type', ['id'=>1,'type'=>$model->service_request_type])->orderBy('name')->all(), 'id', 'name'),
        'pluginOptions'=>[
            'depends'=>['request-service_request_type'],
            'placeholder'=>'-----',
            'url'=>Url::to(['/request/populatedetails'])
        ]
    ]); ?>

    <?= $form->field($model, 'additional_detail')->widget(DepDrop::classname(), [
        'data'=> ArrayHelper::map(RequestAdditionalType::find()->where('deleted != :id and detail_type_id=:detail', ['id'=>1,'detail'=>$model->detail_type])->orderBy('name')->all(), 'id', 'name'),
        'pluginOptions'=>[
            'depends'=>['request-detail_type'],
            'placeholder'=>'-----',
            'url'=>Url::to(['/request/populateadditionals'])
        ]
    ]); ?>

    <?= $form->field($model, 'supplemental_detail')->widget(DepDrop::classname(), [
        'data'=> ArrayHelper::map(RequestSupplementalType::find()->where('deleted != :id and additional_type_id=:additional', ['id'=>1,'additional'=>$model->additional_detail])->orderBy('name')->all(), 'id', 'name'),
        'pluginOptions'=>[
            'depends'=>['request-additional_detail'],
            'placeholder'=>'-----',
            'url'=>Url::to(['/request/populatesupplementals'])
        ]
    ]); ?>

    <?= $form->field($model, 'productIds')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Product::find()->where('customer_id = :id', ['id'=>$model->customer_id])->orderBy('serial_number')->all(), 'id', 'serial_number'),
        'options' => [
            'placeholder' => '-----',
            'multiple' => true
        ],
    ]); ?>

    <?= $form->field($model, 'device')->dropDownList(
            ArrayHelper::map(RequestDevice::find()->where('deleted != :id', ['id'=>1])->orderBy('type, model')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'enhancement')->dropDownList(
            ArrayHelper::map(RequestEnhancement::find()->where('deleted != :id', ['id'=>1])->orderBy('category, material')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'software')->dropDownList(
            ArrayHelper::map(RequestSoftware::find()->where('deleted != :id', ['id'=>1])->orderBy('category, group')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'service_request_status')->dropDownList(
            ArrayHelper::map(RequestStatus::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'escalated_to')->dropDownList(
            ArrayHelper::map(User::find()->where('status_id = :id and role_id != :id', ['id'=>1])->orderBy('username')->all(), 'id', 'username'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'onsite_appointment_datetime')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATETIME,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ]
    ]);?>

    <?= $form->field($model, 'priority')->dropDownList(
            ArrayHelper::map(RequestPriority::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'country')->dropDownList(
            ArrayHelper::map(Country::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>

    <?= $form->field($model, 'service_center')->widget(DepDrop::classname(), [
        'data'=> ArrayHelper::map(RequestServiceCenter::find()->where('deleted != :id and country_id=:country_id', ['id'=>1,'country_id'=>$model->country])->orderBy('name')->all(), 'id', 'name'),
        'options'=>[
            'prompt'=>'-----',
        ],
        'pluginOptions'=>[
            'depends'=>['request-country'],
            'placeholder'=>'-----',
            'url'=>Url::to(['/request/populatecenters'])
        ]
    ]); ?>


    <div class="form-group">
        <label class="control-label col-sm-4 col-sm-left-align"></label>
        <div class="col-sm-8">
           <?= Html::submitButton('Create Service Request', ['class' => 'btn btn-sm btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

    $('#request-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        saveRequest($(this));
        return false;
    });

");
?>
