<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\request\Request */

$this->title = 'New Service Request';

?>
<div class="request-create">

    <?= $this->render('create_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
	$this->registerJs("
	    $('#request-modal').find('#request-modal-title').html('".$this->title."');
	");
?>