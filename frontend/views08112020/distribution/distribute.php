<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use frontend\models\campaign\Campaign;
use frontend\modules\tools\models\user\User;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model frontend\models\cases\Cases */

$this->title = Yii::$app->name . ' - Distribution';
?>


<div class="distribution-create">
	<div class="dashboard-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                Distribution
            </h3>
        </div>
        <div class="panel-body">
	<div class="customer-campaign-form">

		<?php $form = ActiveForm::begin([
			//'layout' => 'horizontal',
			'id' => 'distribution-form',
			        'validateOnSubmit' => false,

			'errorSummaryCssClass' => 'alert alert-danger',
			'options' => ['enctype'=>'multipart/form-data'],
			'enableAjaxValidation' => true,
		]); ?>



		<?= $form->field($model,'campaign_id')->dropDownList(
            ArrayHelper::map(Campaign::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
        );
		?>



		<div id="customer-list-box" style="display:none;">



            <?= $this->render(Url::to('customer_distribution_list'), [
                    'customerDataProvider' => $customerDataProvider,
					'agentDataProvider' => $agentDataProvider,
					'noofcustomers' => $noofcustomers,
            ]) ?>

		</div>

		<?= $form->field($model, 'no_of_customers')->textInput() ?>
		<div class="form-group">
			<label class="control-label col-sm-3 col-sm-left-align"></label>
			<div class="col-sm-8" id='divBtn'>
				<?=Html::Button('Assign Customers', ['class' => 'btn btn-success', 'id' => 'btnsend', 'name' => 'submit' ]) ?>
			
			
			</div>
		</div>

		<?php ActiveForm::end(); ?>

	</div>
	<div class="customer_list" style="display:none" id = "customer_list">

		<div style="clear: both;"></div>

        <div id="distribution-list-widget">

		</div>
	</div>
</div>
		</div>
	</div>
</div>

<?php
    $this->registerJs("

		$('#contactdistribution-campaign_id').change(function (e) {
            if(!$(this).val()) {
				$('#customer-list-box').hide();
                return;
            }
			$('#customer-list-box').show();
			//Loading('customer-list-box',true);
			$.ajax({
              url: $('#contactdistribution-campaign_id').attr('action'),
              type: 'get',
			  data: {'refresh-widget':true, 'campaign_id':$(this).val()},
			  success: function(response) {
				  
			//	alert('in success');
                $('#customer-list-box').html(response);
				  var avblCount=$('#nocForDistribute').val();
					if (avblCount==0)
							$('#divBtn').hide();
					else
						$('#divBtn').show();
				//$('#noofcustomers').append(''+)
              //  Loading('customer-list-box',true);
              }
            });

        });

      
		$('#btnsend').click(function(){
			alert('ddd');
			var avblCount=$('#nocForDistribute').val();
			var perheadCount=$('#contactdistribution-no_of_customers').val();
			var agentkeys = $('#agent_id').yiiGridView('getSelectedRows');
            if (agentkeys==''){
				alert('Check at least one agent id');
					return false;
			}
			alert(avblCount +perheadCount);
			if (avblCount < perheadCount ){
					alert('Number of customers of an agent should be less than available customer count');
					return false;

			}
			else{
				alert('all ok');
				//$('#distribution-form').submit();
				$.ajax({
					
              url: $('#contactdistribution-campaign_id').attr('action'),
              type: 'post',
			  data: {'refresh-widget':true, 'campaign_id':$(this).val()},
			 success: function(response) {
			    //alert(response)
              }
            });
				//return true;
			}
				
		
		});
");

?>
