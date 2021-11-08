<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\campaign\Campaign;
/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\CustomerCampaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-campaign-form">

      <?php $form = ActiveForm::begin([
  'action' => ['cac/create?customer_id='.$model->customer_id],'options' => ['method' => 'post'],
      'layout' => 'horizontal',
      'id' => 'customercampaign-form',
      'errorSummaryCssClass' => 'alert alert-danger',
      'fieldConfig' => [
          'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
          'horizontalCssClasses' => [
              'label' => 'col-sm-3 col-sm-left-align',
              'offset' => 'col-sm-offset-1',
              'wrapper' => 'col-sm-8 error-enabled-custom-field',
              'error' => '',
              'hint' => '',
          ],
      ],
  ]); ?>

  <?= $form->errorSummary($model)?>
<div class="row">
	  <div class="col-sm-12">
    <?= $form->field($model, 'campaign_id')->dropDownList(
            ArrayHelper::map(Campaign::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----'],['enableAjaxValidation' => true]
        ); 
    ?>  </div></div>
     <div id="errmessage" style="color:red;"></div>

   <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success','id'=>'addCampaignBtn']) ?>
    </div>

   

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("

    $('#addCampaignBtn').click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var url=$('#customercampaign-form').attr('action');
       
	$.post(url, $('#customercampaign-form').serialize(),function (data){
              
              $('#errmessage').html(data['customercampaign-campaign_id'][0])
              $('#customercampaign-modal').find('#customercampaign-modal-content').html(data);	});
            return false;
    });

");
?>
