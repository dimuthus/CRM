<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$gridColumns = [
            [   'class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'service_request_id',
                'label'=>'Case ID'
            ],         
                   
           
               ['attribute'=> 'service_request_status',
                'label'=>'Case Status'],

            [   'attribute'=> 'service_request_escalated_to',
                'label'=>'Case Escalated To',
                'format'=>'text'
            ],   
                
             
            
            [
                'attribute'=>'request_creator',
                'label'=>'Case Created by',
                'format'=>'text'
            ],
            [
                'label'=>'Case Creation Date',
                'attribute'=>'request_creation_datetime', 
                'format'=>['date', 'php:d M y']
            ],
            [
                'label'=>'Case Creation Time',
                'attribute'=>'request_creation_datetime', 
                'format'=>['date', 'php:H:i A']
            ],
            [
                'label'=>'Case Last Modified Date & Time',
                'attribute'=>'request_modified_datetime', 
                'format'=>'text'
            ],
            
            
            
              
            [
                'attribute'=>'interaction_id',
                'label'=>'Interaction ID'
            ], 
                'interaction_channel_type',
                'interaction_current_outcome',
            [
                'attribute'=>'interaction_comments',
                'contentOptions'=>['style'=>'min-width:400px']
            ],
            
            
            [
                'attribute'=>'interaction_creator',
                'label'=>'Interaction Created by'
            ],
            [
                'label'=>'Interaction Creation Date',
                'attribute'=>'interaction_creation_datetime', 
                'format'=>['date', 'php:d M y']
            ],
            [
                'label'=>'Interaction Creation Time',
                'attribute'=>'interaction_creation_datetime', 
                'format'=>['date', 'php:H:i A']
            ],
        ];

?>
<div id="report-list">

    <?php \yii\widgets\Pjax::begin(['id' => 'report-widget','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <h5 style="line-height:40px">Report Generated:
    <div id="export-box">
    <?php if(Yii::$app->user->can('Export Report (Summary)')) { ?>
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'showColumnSelector'=>false,
                'exportConfig'=> [
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_PDF => false,
                    ExportMenu::FORMAT_CSV => true,
                      ExportMenu::FORMAT_CSV   => [
                    'label'           => Yii::t('app', 'CSV'),
					],
					ExportMenu::FORMAT_EXCEL_X => [
                    'label'           => Yii::t('app', 'Excel'),
					],                ],
                'dropdownOptions' => [
                    'label' => 'Export',
                    'class' => 'btn btn-sm btn-primary'
                ]
            ]);
        ?>
    <?php } ?>
    </div></h5>
<input type="hidden" id="btnExportToExcel" value="Export To Excel" onclick="ExportToExcel();"/>
    <div style="clear: both;"></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'report-list-widget',
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => $gridColumns
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>
    
</div>
<?php 
//print_r($results);

 ?>
 <style>
#w0-excel5 {
	display:none;
}
#w0-excel2007 {
	display:none;
}
</style>
<script>
    
    function ExportToExcel() {
        var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
           // return function () {
               // if (!table.nodeType) table = document.getElementById(table)
                var ctx = { worksheet: 'Worksheet', table: $('#report-list-widget-container').html() }
                window.location.href = uri + base64(format(template, ctx))
           // }
    }
    
    </script>