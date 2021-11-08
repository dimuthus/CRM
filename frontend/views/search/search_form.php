<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model frontend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-search">


    <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Search Customers</h3></div>
  <div class="panel-body">

    <?php $form = ActiveForm::begin([
        'action' => ['search'],
        'method' => 'get',
        'layout' => 'horizontal',
        'id' => 'search-form',
        'successCssClass' => '',
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
    
	   <div class="row">
	<div class="col-sm-6"> <?= $form->field($searchModel, 'full_name') ?></div>
        <div class="col-sm-6"> <?= $form->field($searchModel, 'mobile_number') ?></div>
    </div>
    <div class="row">
	<div class="col-sm-6"> <?= $form->field($searchModel, 'new_nic') ?></div>
        <div class="col-sm-6"> <?= $form->field($searchModel, 'passport') ?></div>
    </div>
  <div class="row">
	<div class="col-sm-6"><?= $form->field($searchModel, 'account_number')?></div>
	<div class="col-sm-6"><?= $form->field($searchModel, 'card_number')?></div>
	
    </div>
  <div class="row">
	<div class="col-sm-6"> <?= $form->field($searchModel, 'email')->widget(MaskedInput::className(),[  'name' => 'input-36',
    'clientOptions' => [
        'alias' =>  'email']])?></div>
    <div class="col-sm-6">  <?= $form->field($searchModel, 'cif') ?></div>

    </div>
      <div class="row">
	<div class="col-sm-6">  <?= $form->field($searchModel, 'business_reg_number') ?></div>
	<div class="col-sm-6"> <?= $form->field($searchModel, 'dob')->widget(MaskedInput::className(),[   'name' => 'input-32',
    'clientOptions' => ['alias' =>  'yyyy-mm-dd']])?></div>
    </div>
      
  <div class="row">
	<div class="col-sm-6">     <?php if(Yii::$app->user->can('Create Contact')) { ?>
                <?= Html::Button('<span class="glyphicon glyphicon-plus"></span> Add Customer', [
                            'class' => 'btn btn-success btn-sm',
                            'style'=>'display:none',
                            'id'=>'search-create-customer']) ?>  
            <?php } ?> 
                <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span> Search', ['class' => 'btn btn-danger btn-sm','data-loading-text'=>'Searching...']) ?>
            </div>
    </div>
   
  

    
    
       
    <?= $form->errorSummary($searchModel, ['header' => '', 'id' => 'searchError', 'class' => 'alert alert-danger']); ?>



    <?php ActiveForm::end(); ?>
</div>
</div>
</div>

</div>

<?php
$this->registerJs("

    $('#search-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        searchCustomer($(this));
    });

    $('#search-create-customer').click(function () {
        $(this).button('loading');
        navigateToCustomer($('#search-form'));
        return false;
    });
      $('#customersearch-full_name').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
   

");
?>