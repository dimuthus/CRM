<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\complaint\Complaint */

$this->title = 'Update Complaint: ' . ' ' . $model->id;

?>
<div class="complaint-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php
	$this->registerJs("
	    $('#complaint-modal').find('#complaint-modal-title').html('".$this->title."');
	");
?>