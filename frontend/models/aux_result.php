<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */




$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],

    //'full_name',
    ['attribute' => 'first_name', 'format' => 'text',   'label' => 'First Name'],
    ['attribute' => 'group_name', 'format' => 'text',   'label' => 'Group Name'],
    ['attribute' => 'aux_name', 'format' => 'text',   'label' => 'Aux'],
    ['attribute' => 'aux_press_time', 'format' => 'text',   'label' => 'Aux Press Time'],
    ['attribute' => 'aux_release_time', 'format' => 'text',   'label' => 'Aux Release Time'],
    ['attribute' => 'login_time', 'format' => 'text',   'label' => 'Login Time'],
    ['attribute' => 'logout_time', 'format' => 'text',   'label' => 'Logout Time'],
    ['attribute' => 'elapsed_time', 'format' => 'text',   'label' => 'Elapsed Time'],
   
];
?>
<div id="report-list">

   <?php \yii\widgets\Pjax::begin(['id' => 'report-list','enablePushState'=>FALSE,'timeout' => 150000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <h5 style="line-height:40px">Report Generated:
	
	
    <div id="export-box">
    <?php if(Yii::$app->user->can('Reporting Page (Aux)')) { ?>
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
				//'autoXlFormat'=>true,
                'columns' => $gridColumns,
               'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'showColumnSelector'=>false,
                'exportConfig'=> [
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_EXCEL => false,
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