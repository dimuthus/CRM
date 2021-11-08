<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\distribution\Redistribution */

$this->title = 'Redistribute';

?>
<div class="redistribution-create">
	<div class="dashboard-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                Redistribution
            </h3>
        </div>
        <div class="panel-body">

    <?= $this->render('_form', [
        'model' => $model,
		'rd_model' => $rd_model,
	//	'customerDataProvider'=>$customerDataProvider,
		'noofcustomers'=>$noofcustomers,
    ]) ?>

</div>
</div>
</div> </div>    