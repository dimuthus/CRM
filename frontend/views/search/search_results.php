<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
use frontend\models\jmccrypt\JmcCrypt;
use frontend\models\customer\CustomerSearch;
use frontend\models\customer\Customer;


/* @var $this yii\web\View */
/* @var $model frontend\models\Product */
?>
<div class="results-view">

  <div class="panel panel-warning">
  <div class="panel-heading">            
  <h3 class="panel-title"><span class="glyphicon glyphicon-th"></span>Search Result</h3></div>
  <div class="panel-body">

    <?php \yii\widgets\Pjax::begin(['id' => 'products','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $results,
        'id' => 'results-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [

                'label'=>'Full Name',
                 'value'=> 'full_name',
                 'format'=>'raw'


            ],
            [

                'label'=>'Mobile Number',
                 'value'=>'mobile_number', 
                 'format'=>'raw'


            ],
            
            'new_nic',
            // 'old_nic',
            'account_number',
			//'company_name',
			//'bmb_ticket_number',
			//'serial_number',
			//'machine_number',
             // 'mobile_number',

        ],
        'rowOptions'   => function ($model, $key, $index, $grid) {
              $jmcIns = new JmcCrypt();
              $hashID= $jmcIns->HashMe($model['id']);
            return ['data-jmcid' => $hashID,'data-id' => $model['id']];
        },
    ]); ?>

    <?php
    $this->registerJs("

        /*
        listener to handle click event of view product item
        */
        $('#results-widget tbody tr').click(function (e) {
            var thisRow = $(this);

            var jmcid = $(this).data('jmcid');
            var id = $(this).data('id');
            if(id != undefined) {
                window.location.href = '".Url::to(['/customer'])."/'+id+'?jmc='+jmcid;
            }
        });

    ");
    ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
  </div>
    </div>
