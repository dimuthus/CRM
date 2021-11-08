<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\complaint\Complaint */

$this->title = 'New Complaint Details';

?>
<div class="complaint-create">

     <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
	$this->registerJs("
	    $('#complaint-modal').find('#complaint-modal-title').html('".$this->title."');
	");
?>
