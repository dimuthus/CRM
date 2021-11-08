<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$gridColumns = [

			'module_name',
		
				[
			 'attribute'=>'old_row_data',
				'format'=>'raw',
			 'label'=>'old_row_data'
			],
	[
			 'attribute'=>'new_row_data',
				'format'=>'raw',
			 'label'=>'new_row_data'
			],
			'dml_type',
			'dml_timestamp',
			'username',
			'ip_address'

        ];

?>
<div id="report-list">

    <?php \yii\widgets\Pjax::begin(['id' => 'report-widget','enablePushState'=>FALSE,'timeout' => 1000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <h5 style="line-height:40px">Data Generated:


    <div id="export-box">
    <?php if(Yii::$app->user->can('Export Report (Inbound)')) { ?>
        <?= 
		
		ExportMenu::widget([
                'dataProvider' => $dataProvider,
				//'autoXlFormat'=>true,
                'columns' => $gridColumns,
                'showColumnSelector'=>false,
						    'floatHeader'=>true,

				'filename' => 'Activity-Report',
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
					],
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
<div class="output" style="height: 300px; overflow-y: auto">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'report-list-widget',
       // 'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => $gridColumns
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>
</div>
</div>
  <?php
    $this->registerJs("
		/*
        $('td').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if(e.target == this && id != undefined)
                location.href = '" . Url::to(['/tools/user']) . "/' + id;
        });*/
		$('.table').DataTable({ \"scrollX\": true,   \"scrollY\":        \"200px\",
        \"scrollCollapse\": true,
        \"paging\":         false,
		
});
    ");
    ?>
<style>

.summary {
	display:none;
}
#w0-excel5 .summary {
	display:none;
}
#w0-excel2007 {
	display:none;
}
</style>
