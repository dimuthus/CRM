<?php

use yii\helpers\Html;


/* @var $this yii\web\View */

$this->title = 'Add Customer Event';

?>
<div class="customerevents-create">

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
	    $('#customerevents-modal').find('#customerevents-modal-title').html('".$this->title."');
	");
?>
