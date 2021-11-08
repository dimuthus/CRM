<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\customer\Salutation;
use frontend\models\customer\Gender;
use frontend\models\customer\CustomerType;
use frontend\models\customer\CustomerStatus;
use frontend\models\customer\CustomerLanguage;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use frontend\models\jmccrypt\JmcCrypt;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\customer\Customer */
/* @var $form ActiveForm */
?>


<div class="customer-create_form">

    <?php 
    
       $jmcIns = new JmcCrypt();
       $hashID= $jmcIns->HashMe($model['id']);
      // die($jmcIns);
    
    $form = ActiveForm::begin(); ?>
	
	  <div class="row">
	  <div class="col-sm-4">
	    <?= $form->field($model, 'salutation_id');
		
		//->dropDownList(
        //    ArrayHelper::map(Salutation::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'name', 'name'),
        //    ['prompt'=>'--------']
       // );
    ?>
	   </div>
	  <div class="col-sm-4"><?= $form->field($model, 'full_name') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'preferred_name') ?></div>
	  </div>
	  <div class="row">
	  <div class="col-sm-4"> <?= $form->field($model, 'gender')
	  //->dropDownlist(
		//	ArrayHelper::map(Gender::find()->all(),'gender','gender'),
		//	['prompt'=>'--------']
	  //) 
	?></div>
	  <div class="col-sm-4">	<?= $form->field($model, 'dob')->widget(DateControl::classname(), [
                 'type'=>DateControl::FORMAT_DATE,
                 'options' => [
                 'removeButton' => false,
                'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
				
            ]
        ]
    ]
    );?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'preferred_language')->dropDownlist(
			ArrayHelper::map(CustomerLanguage::find()->all(),'id','name'),
			['prompt'=>'--------']
	) ?></div>
	  </div>
     <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'new_nic') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'old_nic') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'driving_license') ?></div>
	  </div>
       
	  <div class="row">
              <div class="col-sm-4">
			  <?= $form->field($model, 't_pin')->passwordInput(['id'=>'t_pin','readonly'=> true]) ?> 
         </div>
	  <div class="col-sm-4"><?= $form->field($model, 'marital_status') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'spouse_name') ?></div>
	  </div>
	  
	    <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'town') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'district') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'postal_code') ?></div>
	  </div>
	  
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'address1') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'address2') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'address3') ?></div>
	  </div>
	  
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'alternate_town') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'alternate_district') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'alternate_postal_code') ?></div>
	  </div>
	  
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'alternate_address1') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'alternate_address2') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'alternate_address3') ?></div>
	  </div>
      
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'passport') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'citizenship') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'profession') ?></div>
	  </div>
	  
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'business_name') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'business_reg_number') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'business_registered_date') ?></div>
	  </div>
	  
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'mobile_number')->widget(MaskedInput::className(),[ 'name' => 'input-33',
            'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => '',
            'autoGroup' => true,
			'rightAlign' => false

    ],]) ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'alternative_number')->widget(MaskedInput::className(),[ 'name' => 'input-33',
            'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => '',
            'autoGroup' => true,
			'rightAlign' => false

    ],]) ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'email') ?></div>
	  </div>
	 
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'branch') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'staff_pf') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'relationship_manager') ?></div>
	  </div>
	  
	   <div class="row">
	  <div class="col-sm-4">
	  <!--< ?= $form->field($model, 'customer_status')->dropDownList(
            ArrayHelper::map(CustomerStatus::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'name', 'name'),
            ['prompt'=>'--------']
        ); ?> -->
		<?= $form->field($model, 'customer_status') ?>
		</div>
	  <div class="col-sm-4">
	  		<?= $form->field($model, 'customer_type') ?>

	  <!--< ?= $form->field($model, 'customer_type')->dropDownList(
            ArrayHelper::map(CustomerType::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'name', 'name'),
            ['prompt'=>'--------']
        ); ?> -->
	  </div>
	  <div class="col-sm-4"><?= $form->field($model, 'customer_since')->widget(DateControl::classname(), [
                 'type'=>DateControl::FORMAT_DATE,
                 'options' => [
                 'removeButton' => false,
                'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
				
            ]
        ]
    ]
    );?></div>
	  </div>
	  
      <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'employer') ?></div>
	  <div class="col-sm-4">
	  <?php if ($model->created_by !=999999)
	   echo $form->field($model, 'cif')->textInput(['readonly'=> false]);
      else
	  	echo  $form->field($model, 'cif')->textInput(['readonly'=> true]) 
	?>
     
	  </div>
	  <div class="col-sm-4"><?= $form->field($model, 'vip_flag')->radioList(['Y' => 'yes', 'N' => 'No']); ?></div>
	  </div> 
      
	  <div class="row">
	  <div class="col-sm-4"><?= $form->field($model, 'group_code') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'group_description') ?></div>
	  <div class="col-sm-4"><?= $form->field($model, 'province') ?></div>
	  </div> 
       <!-- < ?= $form->field($model, 'created_by') ?>
        < ?= $form->field($model, 'updated_by') ?>
        < ?= $form->field($model, 'created_datetime') ?>
        < ?= $form->field($model, 'last_updated_datetime') ?>
        < ?= $form->field($model, 'deleted') ?> -->
       
        
        
       
        
    
        <div class="row">
        <div class="col-sm-4">
            <?= Html::submitButton($model->isNewRecord ? 'Create Customer' : 'Save Changes', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
    
		 
		<?php  //= Html::a('Cancel', ['/search/'], ['class'=>'btn btn-primary btn-sm']) ?>
		<?php  if($model->isNewRecord){ ?>
		<?= Html::a('Cancel', ['/search/'], ['class'=>'btn btn-success btn-sm','id'=>'cancelbutton1']) ?>
		<?php  }else{ ?>
                <?=  Html::a('Cancel', ['/customer/'.$model->id.'?jmc='.$hashID], ['class'=>'btn btn-primary btn-sm','id'=>'cancelbutton2']) ?>
		<?php  }  ?>		
	    </div>	 
	</div>
    <?php ActiveForm::end(); ?>

</div><!-- customer-create_form -->
<?php
$this->registerJs("

  
      $('#customer-full_name').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });

");
?>