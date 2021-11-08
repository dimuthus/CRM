<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

                
            
$gridColumns = [

			[
			 'attribute'=>'respondent_id',
			 'format'=>'text',
			 'label'=>' Customer ID'
			],

				[
			 'attribute'=>'case',
				'format'=>'text',
			 'label'=>'Case#'
			],


			[
			 'attribute'=>'full_name',
				'format'=>'text',
			 'label'=>'Customer Name'
			],	
			
			[
			 'attribute'=>'question',
				'format'=>'text',
			 'label'=>'Question'
			],				
			
			


			[
			'attribute'=>'answer',
			'format'=>'text',
			'label'=>'Response'
			],
			
/*[
			'attribute'=>'Q2',
			'format'=>'text',
			'label'=>'Q2'
			],
			[
			'attribute'=>'Q3',
			'format'=>'text',
			'label'=>'Q3'
			],
			[
			 'format'=>'html',
			 'label'=>'Other Comment',
			 'value'=>function($model,$index,$key,$column){
			 				


			 				 	$campaign_id=0;
			 	$customer_id=$model['respondent_id'];
			 		$campaign_id=137;
			 	if($model['name']=='INBOUND-CSAT'){
			 		$campaign_id=75;

			 	}
			 	
			 
			 	$case= $model['case'];
			  	if($model['Q3']=="Or any other comments that you have"){
			 	$query= "SELECT other_comment FROM crm_survey_result WHERE survey_response_id=$campaign_id AND respondent_id=$customer_id  AND answer=6 AND case_id=$case" ;

			 	$command= Yii::$app->db->createCommand($query);
			 	$result=$command->queryOne();

			 	
			 	return $result['other_comment'];
			 } else return "";	



			 }
			],
			*/
			
			/*
			[
			'attribute'=>'Q4',
			'format'=>'text',
			'label'=>'Q4'
			],
			[
			'attribute'=>'Q5',
			'format'=>'text',
			'label'=>'Q5'
			],
			[
			'attribute'=>'Q6',
			'format'=>'text',
			'label'=>'Q6'
			],
	*/

			[
			 'attribute'=>'CreateDate',
				'format'=>'text',
			 'label'=>'Survey Created Date'
			],

			


			[
			 'attribute'=>'name',
				'format'=>'text',
			 'label'=>'Survey Type'
			]
			
			
			

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
				'filename' => 'CSAT-DATA',
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
