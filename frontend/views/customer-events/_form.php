<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm; // this doesnt not work....
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Events;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

//echo('mmm='.$model->customer_id);
//die('GGGGGGGG2');
?>

<div class="customerevents-form">
  <?php $form = ActiveForm::begin([
  'action' => ['customer-events/create?customer_id='.$model->customer_id],'options' => ['method' => 'post'],
      'layout' => 'horizontal',
      'id' => 'customerevents-form',
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

  <?= Html::activeHiddenInput($model, 'customer_id') ;?>

    <?= $form->field($model, 'event_id')->dropDownList(
              ArrayHelper::map(Events::find()->where('is_deleted != :id', ['id'=>1])->orderBy('event_name')->all(), 'id', 'event_name'),
          //    ['options' => [ 158 => ['selected ' => true]]],
              ['prompt'=>'-----']
          );
      ?>

    <div class="form-group" align="center">
        <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("

    $('#customerevents-form').submit(function (e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      var case_id = $('#customer_id').val();

      var reload_url = '".Url::to(['loadevents'])."?customer_id='+case_id ;
      saveEvents($(this),reload_url,case_id);
      return false;

    });

");


?>
