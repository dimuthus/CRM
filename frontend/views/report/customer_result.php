<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$gridColumns = [

			
			// [
			 // 'attribute'=>'salutation',
			 // 'format'=>'text',
			 // 'label'=>'Title'
			// ],


			// [
			 // 'attribute'=>'full_name',
				// 'format'=>'text',
			 // 'label'=>'Full Name'
			// ],
			
'full_nameD',
'mobile_numberD',
'address1',
'address2',
'address3',


			

        ];

?>
<div id="report-list">

    <?php \yii\widgets\Pjax::begin(['id' => 'report-widget','enablePushState'=>FALSE,'timeout' => 1000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <h5 style="line-height:40px">Data Generated:


    <div id="export-box">
        <?= ExportMenu::widget([
                'dataProvider' => $dataProvider,
				//'autoXlFormat'=>true,
                'columns' => $gridColumns,
                'showColumnSelector'=>false,
								'filename' => 'Customer_Data',
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
<style>
#w0-excel5 {
	display:none;
}
#w0-excel2007 {
	display:none;
}
</style>
<?php
    $this->registerJs("
        
     	$(document).ready(function () {
			
		})
		
			$('.table').DataTable({
		
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel'
		]
	});
		
		");
		?>