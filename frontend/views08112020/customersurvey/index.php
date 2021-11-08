<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<div class="customer-view">
  <h4>Survey</h4>

  
  <?php 
  //echo   '<input type="text" value='.$model->id.' />';
  //var_dump($customerSurveyDataProvider);
  ?>
      <?=
                            Html::button('<span class="glyphicon glyphicon-plus"></span> Add New Survey', [
                                'value' => Url::to(['customersurvey/create', 'customer_id' => $model->id]),
                                'class' => 'btn btn-xs btn-success console-button',
                                'id' => 'create-customer-survey-modal-button',
                                'data-loading-text' => 'Loading...'
                            ])
                            ?>
  
      <?php \yii\widgets\Pjax::begin(['id' => 'survey','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

  <?= 
 // var_dump($customerSurveyDataProvider);
//$customerSurveyDataProvider="";

GridView::widget([
					'id'=>'survey-widget',
                                        'layout'=>"{items}\n{summary}\n{pager}",

					'dataProvider' => $CustomerSurveys,
					'columns' => [
					['class' => 'yii\grid\SerialColumn'],
					[
						'attribute'=>'surveyName.name', //
						//'contentOptions'=>['class'=>'col-sm-2']
					],
					[
						'attribute'=>'respondent_id',
						//'contentOptions'=>['class'=>'col-sm-2']
					],
                           [
                'format' => 'raw',
                'value' => function ($model) {   
   
                    return '<div class="edit-request-icon"><a data-id="'.$model->id.'" class="glyphicon glyphicon-menu-hamburger"></a></div>';
                },
				'label' => 'View Details',
            ]
            		             	
			],
                        
                            'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
                  
      
]);
	?>

   
  <?php
        Modal::begin([
            'header' => '<h4 id="customersurvey-modal-title"></h4>',
            'id' => 'customersurvey-modal',
        ]);

        echo '<div id="customersurvey-modal-content"></div>';
        
        Modal::end();
    ?> 
  
 <?php
    $this->registerJs("


        $('#create-customer-survey-modal-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            var url = '".Url::to(['customersurvey/create'])."';
          //  $('#interaction-view').load(url);
            $('#survey-widget tbody tr').removeClass('active-case-row');
            var modal_content = $('#customersurvey-modal').find('#customersurvey-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
                $('#customersurvey-modal').modal('show');
                btn.button('reset');
            });
        });




        $('#survey-widget tbody tr').click(function (e) {
        alert('test');
         if($(this).hasClass('active-request-row'))
                return;
               $(this).siblings().removeClass('active-request-row');
            $(this).addClass('active-request-row');
    });
             

    
    ");
    ?>
  <?php \yii\widgets\Pjax::end(); ?>
</div>
