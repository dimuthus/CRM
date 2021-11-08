<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\models\campaign\Campaign;
use kartik\widgets\FileInput;
$this->title = Yii::$app->name . ' - Master Customer Upload';
?>
<!--<div class="panel panel-info" style="margin-top: 20px;">-->

<div class="customer-upload">
<div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span> Product Data Upload</h3></div>
  <div class="panel-body">
      
      
 <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data','id' => 'upload-form']]);?>

          
            <div class="row">
          <div class="col-sm-6"><?=
        $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => '*','multiple' => false],
         'pluginOptions' => [
        'showPreview' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false,
        'mainClass' => 'input-group-xs'
    ]
]);
    ?> </div>
      </div>


    <div class="form-group">
        <?= Html::submitButton('Upload',['class'=>'btn btn-primary','id'=>'upload_save']) ?>
    </div>
<div>
    <a href="../uploads/daily_upload_product.csv" > Uploader Template </a>
</div>

	  <?php if (Yii::$app->session->hasFlash('success')): ?>
	  <div class="alert alert-success alert-dismissable">
		  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		  <h4><i class="icon fa fa-check"></i>Saved!</h4>
		  <?= Yii::$app->session->getFlash('success') ?>
	  </div>
	<?php endif; ?>
	<div id='loading'> Loading ... </div>
	<?php
		echo "<div id='modalContent'><div style=\"text-align:center\"><img src=\"".Url::to('@web/images/loading.gif')."\"></div></div>";
	?>
    <?php
    if (isset($resultData)){
     //print_r($resultData);
 //die(3567);

    ?>
    <?= $this->render('daily_product_update_result', [
                    'allModels' => $resultData,
                ])  ?>
<?php
    }
    ?>
<?php ActiveForm::end(); ?>
      
      
      
  </div>
</div>
</div>


<!--</div>-->
<?php
$this->registerJs("


	$('document').ready(function(){
		document.getElementById('loading').style.display = 'none';
		document.getElementById('modalContent').style.display = 'none';
	});


");
?>
