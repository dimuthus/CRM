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
			 'attribute'=>'campaign',
				'format'=>'text',
			 'label'=>'Campaign Name'
			],
			[
			 'attribute'=>'distributed_to',
				'format'=>'text',
			 'label'=>'Distributed To'
			],

			[
			 'attribute'=>'distributed_by',
				'format'=>'text',
			 'label'=>'Distributed By'
			],

			[
			 'attribute'=>'distributed_date',
				'format'=>'text',
			 'label'=>'Distributed Datetime'
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
								'filename' => 'Distributed_Customer_Data',
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
