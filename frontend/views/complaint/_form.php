<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;




use frontend\models\complaint\ComplaintBrand;
use frontend\models\complaint\ComplaintSubBrand;
use frontend\models\complaint\ComplaintProduct;
use frontend\models\complaint\ComplaintPacksize;
use frontend\models\complaint\ComplaintColor;
use frontend\models\complaint\ComplaintUserType;
use frontend\models\complaint\ComplaintProofOfPurchase;
use \frontend\models\cases\Cases;
/* @var $this yii\web\View */
/* @var $model frontend\models\complaint\Complaint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="complaint-form">
    
        <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'complaint-form',
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
 <?= $form->errorSummary($model, ['header' => '']); ?>
       <?= Html::activeHiddenInput($model, 'customer_id') ;?>

         <?= $form->field($model, 'case_id')->dropDownList(
            ArrayHelper::map(Cases::find()->where('customer_id= :id', ['id'=>$model->customer_id])->orderBy('creation_datetime')->all(), 'case_id', 'case_id'),
            ['prompt'=>'-----']
        ); 
    ?>
       
        
            <?= $form->field($model, 'brand_id')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map(ComplaintBrand::find()->where('deleted != :id and id=:id', ['id'=>1,'id'=>$model->brand_id])->orderBy('name')->all(), 'id', 'name'),
                'pluginOptions'=>[
                    'depends'=>['complaint-case_id'],
                  //  'placeholder'=>'-----',
                    'url'=>Url::to(['/complaint/populatebrand'])
                ]
            ]); ?> 
       

 <?= $form->field($model, 'subbrand_id')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map(ComplaintSubBrand::find()->where('deleted != :id and id=:id', ['id'=>1,'id'=>$model->subbrand_id])->orderBy('name')->all(), 'id', 'name'),
                'pluginOptions'=>[
                    'depends'=>['complaint-case_id'],
                    'placeholder'=>'-----',
                    'url'=>Url::to(['/complaint/populatesubbrand'])
                ]
            ]); ?>

      <?= $form->field($model, 'product_id')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map(ComplaintProduct::find()->where('deleted != :id and id=:id', ['id'=>1,'id'=>$model->product_id])->orderBy('name')->all(), 'id', 'name'),
                'pluginOptions'=>[
                    'depends'=>['complaint-case_id'],
                    'placeholder'=>'-----',
                    'url'=>Url::to(['/complaint/populateproduct'])
                ]
            ]); ?>
    
      <?= $form->field($model, 'packsize_id')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map(ComplaintPacksize::find()->where('deleted != :id and id=:id', ['id'=>1,'id'=>$model->packsize_id])->orderBy('name')->all(), 'id', 'name'),
                'pluginOptions'=>[
                    'depends'=>['complaint-case_id'],
                    'placeholder'=>'-----',
                    'url'=>Url::to(['/complaint/populatepacksize'])
                ]
            ]); ?>
   
       
             <?= $form->field($model, 'color_id')->widget(DepDrop::classname(), [
                'data'=> ArrayHelper::map(ComplaintColor::find()->where('deleted != :id and product_id=:product_id', ['id'=>1,'product_id'=>$model->product_id])->orderBy('name')->all(), 'id', 'name'),
                'pluginOptions'=>[
                    'depends'=>['complaint-product_id'],
                    'placeholder'=>'-----',
                    'url'=>Url::to(['/complaint/populatecolor'])
                ]
            ]); ?>
       
  
   
     <?= $form->field($model, 'batch_no')->textInput(['maxlength' => 100]) ?>
    
       <?= $form->field($model, 'user_type_id')->dropDownList(
            ArrayHelper::map(ComplaintUserType::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        ); 
    ?>
    
      <?= $form->field($model, 'purchase_date')->widget(DateControl::classname(), [
        'type'=>DateControl::FORMAT_DATE,
        'options' => [
            'removeButton' => false,
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
                //'startDate' => date("d M Y", strtotime("0 day", strtotime(date('d M Y')))),
            ]
        ]
    ]);?>
    
    <?= $form->field($model, 'place_of_purchase')->textInput() ?>
    
    <?= $form->field($model, 'description')->textArea(['row' => 2]) ?>
    
      <?= $form->field($model, 'proof_of_purchase_id')->dropDownList(
            ArrayHelper::map(ComplaintProofOfPurchase::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        )->label('Exchange Complete'); 
    ?>
   
  

    <div class="form-group">
        
             <label class="control-label col-sm-4 col-sm-left-align"></label>
        <div class="col-sm-8">
            <?= Html::submitButton($model->isNewRecord ? 'Create Complaint' : 'Save Changes',  
            ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm',
            'data-loading-text'=>'Saving...']) ?>
        </div>
    
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

    $('#complaint-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        saveComplaint($(this));
        return false;
    });

");
?>