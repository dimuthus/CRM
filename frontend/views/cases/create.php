<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\cases\Cases */

$this->title = 'New Case';

?>
<div class="cases-create">
<?php    
   if ($campaign_id==0)
       $form="_form";
    else
       $form="_form_out";
    ?>
    
     <?= $this->render( $form, ['model' => $model,'campaign_id'=>$campaign_id ]) ?>


       

</div>

<?php
	$this->registerJs("
	    $('#case-modal').find('#case-modal-title').html('".$this->title."');
	");
?>
