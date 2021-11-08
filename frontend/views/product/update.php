<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\product\Product */

$this->title = 'Update Product: ' . $model->product_name;

?>
<div class="product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
	$this->registerJs("
	    $('#product-modal').find('#product-modal-title').html('".$this->title."');
	");
?>
