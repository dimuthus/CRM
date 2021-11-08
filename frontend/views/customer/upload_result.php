<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;

?>
<div class="customer-view">

    <h4>Upload Result</h4>
    <?php

 //print_r($allModels);
 //die(3567);
    ?>
    <?php \yii\widgets\Pjax::begin(['id' => 'upload-widget','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $allModels,
        'id' => 'upload-widget',
        'layout'=>"{summary}\n{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'Full Name',
			'Registered Contact'  ,
			'Mobile'  ,
			'Account Type'  ,
			'Account Number'  ,
                        'Email',
                        'DigtalProduct',
			'Status'
        ],
    ]); ?>
       <?php \yii\widgets\Pjax::end(); ?>
</div>
