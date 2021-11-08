<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$gridColumns = [

			[
			 'attribute'=>'customer_id',
				'format'=>'text',
			 'label'=>'Customer ID'
			],


			[
			 'attribute'=>'event_name',
				'format'=>'text',
			 'label'=>'Event Name'
			],
			[
			 'attribute'=>'event_location',
				'format'=>'text',
			 'label'=>'Event Location'
			],

			[
			 'attribute'=>'event_month',
				'format'=>'text',
			 'label'=>'Event Month'
			],

			[
			 'attribute'=>'event_year',
				'format'=>'text',
			 'label'=>'Event Year'
			],


			[
			 'attribute'=>'created_by',
				'format'=>'text',
			 'label'=>'Added By'
			],

			[
			 'attribute'=>'created_datetime',
				'format'=>'text',
			 'label'=>'Joined Datetime'
			],

        ];

?>
<div id="report-list">

    <?php \yii\widgets\Pjax::begin(['id' => 'report-widget','enablePushState'=>FALSE,'timeout' => 1000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <h5 style="line-height:40px">Data Generated:


    <div id="export-box">
    <?php if(Yii::$app->user->can('Export Report (Complete)')) { ?>
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
				//'autoXlFormat'=>true,
                'columns' => $gridColumns,
                'showColumnSelector'=>false,
								'filename' => 'Custmoer_Events_Data',
                'exportConfig'=> [
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_EXCEL => true,

                    /*ExportMenu::FORMAT_EXCEL_X => [
                        'label' => Yii::t('kvexport', 'Excel'),
                    ]*/
                ],
                'dropdownOptions' => [
                    'label' => 'Export',
                    'class' => 'btn btn-sm btn-primary'
                ]
            ]);
        ?>
    <?php } ?>
    </div></h5>
    <div style="clear: both;"></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'report-list-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => $gridColumns
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>

</div>
