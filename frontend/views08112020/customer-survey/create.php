<?php

use yii\helpers\Html;


/* @var $this yii\web\View */

$this->title = 'Add Customer Survey';

?>
<div class="customersurvey-create">

    <?php
    //echo 'jalis';
    //die('GGGGGGGG');

     ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
	$this->registerJs("
	    $('#customersurvey-modal').find('#customersurvey-modal-title').html('".$this->title."');
	");
?>
