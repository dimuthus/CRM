<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\interaction\Interaction */

$this->title = 'Update Interaction: ' . $model->id;

?>
<div class="interaction-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
	$this->registerJs("
	    $('#interaction-modal').find('#interaction-modal-title').html('".$this->title."');
	");
?>
