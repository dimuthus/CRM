<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\cases\Cases */

$this->title = 'Update Case: ' . $model->case_id;

?>
<div class="case-update">
<?php    
   if ($campaign_id==0)
       $form="_form_update";
    else
       $form="_form_out_update";
    ?>
    <?= 
	
	
$this->render($form, [
        'model' => $model,'campaign_id'=>$campaign_id
    ]) ?>

</div>

<?php
    $this->registerJs("
        $('#case-modal').find('#case-modal-title').html('".$this->title."');
    ");
?>
