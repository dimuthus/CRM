<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div id="case-list">

    <?php \yii\widgets\Pjax::begin(['id' => 'case-list','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'case-list-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // [
                // 'attribute'=>'customer.full_name', 
                // 'contentOptions'=>['style'=>'width: 100px;']
            // ],
            [
                'attribute'=>'case_id', 
                'contentOptions'=>['style'=>'width: 100px;']
            ],
           
         
            'caseStatus.name',
            
			[
                'attribute'=>'escalatedTo.username',
                'label'=>'Escalated To'
            ],
           // 'priority.name',
            [
                'attribute'=>'created_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
            
			[
                'attribute'=>'createdBy.username',
                'label'=>'Created By'
            ],
            [
                'header' => 'Days Aging',
                'format' => 'raw',
                'value' => function ($model) {
                    $today = new DateTime();
                    $created_on = new DateTime($model->created_datetime);
                    $interval = $today->diff($created_on);
                   // $diff = abs(date("Y m d") - strtotime($model->creation_datetime));
                   // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));             
                    //return $days;
					//echo $interval->format('%R%a'); // use for point out relation: smaller/greater
					//echo $interval->days;
					//var_dump($interval);
					//die();
					if ($interval->days==0)
                    return $interval->days;
					else
					return $interval->days+1;
                },
            ],
        ],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
    ]); ?>

    <?php
    $this->registerJs("

        /*
        listener to handle click event of view interaction item
        */
        $('#case-list-widget tbody tr').click(function (e) {
            //alert('xx');
            var thisRow = $(this);
            var id = $(this).data('id');
            if(id != undefined) {
           
                loadCustomerCase('".Url::to(['search/loadcustomercase'])."/'+id);
               
            }
        });
		
				$('.table').DataTable();


    ");
    ?>

    <?php \yii\widgets\Pjax::end(); ?>
    
</div>





