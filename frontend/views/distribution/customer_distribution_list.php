<?php
use kartik\grid\GridView;
use yii\helpers\Url;

?>

<?php \yii\widgets\Pjax::begin(['id' => 'distribution_list','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
	
<div id="no-of-customers"><h5>No of customers available to distribute: <?= $noofcustomers ?><h5/></div>
<input type='hidden' id='nocForDistribute' value="<?= $noofcustomers ?>">
<div class="col-sm-12">

<?= GridView::widget([
					'id'=>'customer_id',
					'dataProvider' => $customerDataProvider,
					'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					// [
						// 'attribute'=>'cif',
						// 'contentOptions'=>['class'=>'col-sm-2']
					// ],
					[
						'attribute'=>'full_name',
                'contentOptions' => ['style' => 'width:25px; white-space: normal;'],
					],
					
					
			],
]);
	?>
</div>
<div class="col-sm-12">
	<?=
				GridView::widget([
				'id'=>'agent_id',
				'dataProvider' => $agentDataProvider,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					[
						'attribute'=>'username',
                'contentOptions' => ['style' => 'width:25px; white-space: normal;'],
					],				
					[
						'class' => 'yii\grid\CheckboxColumn',
                'contentOptions' => ['style' => 'width:25px; white-space: normal;'],
					]
				],
			]); 
			
			?>

</div>
<?php \yii\widgets\Pjax::end(); ?>
		
