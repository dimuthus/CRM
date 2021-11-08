<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\campaign\Campaign;
use frontend\modules\tools\models\user\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\distribution\Redistribution */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reedistribution-form">

    <?php
    
    
    $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'agent_id')->dropDownList(
            ArrayHelper::map(User::find()->where('status_id = :id and role_id != :id', ['id'=>1])->orderBy('username')->all(), 'id', 'username'),
            ['prompt'=>'-----']
        ); 
    ?>
    
	<?= $form->field($model,'campaign_id')->dropDownList(
            ArrayHelper::map(Campaign::find()->andwhere('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'-----']
		);
	?> 


<input type='hidden' id='nocForReDistribute' value="<?= $noofcustomers ?>">

	<div id="customer-list-box" style="display:none;">

		<?= $this->render(Url::to('distributed_list'), [
						//	'customerDataProvider'=>$customerDataProvider,
							'noofcustomers'=>$noofcustomers,
						//	'rd_model' => $rd_model,
						//	'model' => $model,
				]) ?>
	</div>
		 <?= $form->field($rd_model, 'agent_id')->dropDownList(
            ArrayHelper::map(User::find()->where('status_id = :id and role_id != :id', ['id'=>1])->orderBy('username')->all(), 'id', 'username'),
            ['prompt'=>'-----']
        ); 
    ?>

		<?= $form->field($model, 'no_of_customers')->textInput() ?>

		<div class="form-group">
			<?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id'=>'submit']) ?>
		</div>



	<?php ActiveForm::end(); ?>
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
			var agentid = $('#contactdistribution-agent_id').val();
			//alert(agentid);
			$.ajax({
              url: $('#contactdistribution-campaign_id').attr('action'),
              type: 'get',
			  data: {'refresh-widget':true, 'campaign_id':$(this).val(), 'agent_id':agentid},
			  success: function(response) {
				        $('#customer-list-box').html(response);
				//$('#noofcustomers').append(''+)
              //  Loading('customer-list-box',true);
              }
            });

        });

		$('#redistribution-form').on('beforesubmit',function(e){

				e.preventDefault();
                e.stopImmediatePropagation();
				//alert('in before send');


		});
	$('#submit').click(function(){
			var avblCount=$('#nocForReDistribute').val();
			var perheadCount=$('#contactdistribution-no_of_customers').val();
			if (avblCount < perheadCount ){
					alert('Number of customers of an agent should be less than available customer count');
					return false;

			}
				else{
					return true;
				}
				
		
		});

	");
?>
