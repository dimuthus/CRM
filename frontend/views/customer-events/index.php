<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\campaign\CustomerCampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//$this->title = Yii::$app->name . ' - Events';

Modal::begin([
    'header' => '<h4 id="customerevents-modal-title"></h4>',
    'id' => 'customerevents-modal',
]);

echo '<div id="customerevents-modal-content"></div>';

Modal::end();
?>
<div id="customerevents-view">

    <h4>Events</h4>

    <?php // echo $this->render('_search', ['model' => $searchModel]);
     $cust_id = $customer_id;
    ?>
    <input hidden type="text" name="customer_id" id="customer_id" value="<?php echo $cust_id ?>"/>

    <?php //if(Yii::$app->user->can('Create Interaction')) { ?>
        <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Add Customer Event', [
                                        'value'=>Url::to(['customer-events/create','customer_id' => $model->id]),
                                        'class' => 'btn btn-xs btn-success console-button',
                                        'id'=>'create-customerevents-modal-button',
                                        'data-loading-text'=>'Loading...'
        ]) ?>

    <?php //} end if statement ?>

    <div style="clear: both;"></div>
    <?php \yii\widgets\Pjax::begin(['id' => 'customerevents','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $CustomerEvents,
        'id' => 'customerevents-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
				'attribute'=>'eventId.event_name',

            ],
          'eventType.event_type',
          'createdBy.username',
          'created_datetime',
        ],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
    ]); ?>

    <?php

    $this->registerJs("

        /*
        listener to handle click event of view customer event item
        */
        $('#customerevents-widget tbody tr').click(function (e) {
            var thisRow = $(this);
            var id = $(this).data('id');
            var modal_content = $('#customerevents-modal').find('#customerevents-modal-content');
            if(id != undefined) {
                modal_content.html('').load( '".Url::to(['customer-events/view'])."?id=' + id, function() {
                    $('#customerevents-modal').modal('show');
                });


            }
        });

        /*
        listener to handle click event of add event button
        */
        $('#create-customerevents-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var modal_content = $('#customerevents-modal').find('#customerevents-modal-content');

            modal_content.html('').load( $(this).attr('value'), function() {
                $('#customerevents-modal').modal('show');
                btn.button('reset');
            });
        });
    ");
    ?>

    <?php \yii\widgets\Pjax::end(); ?>

</div>
