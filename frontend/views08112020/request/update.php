<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\request\Request */

$this->title = 'Update Service Request: ' . $model->service_request_id;

?>
<div class="request-update">

    <?= $this->render('update_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
    $this->registerJs("
        $('#request-modal').find('#request-modal-title').html('".$this->title."');
    ");
?>