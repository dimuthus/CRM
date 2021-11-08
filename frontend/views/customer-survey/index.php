<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<div class="customer-view">
  <h4>Campaign-Survey</h4>


  <?php

  echo   '<input type="hidden" value='.$model->id.' id="customer_id" />';
  //var_dump($customerSurveyDataProvider);
  ?>


      <?=
                            Html::button('<span class="glyphicon glyphicon-plus"></span> Add New Survey', [
                                'value' => Url::to(['customer-survey/create', 'respondent_id' => $model->id]),
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
            'label'=>'Campaign Name'
					],
					[

						'attribute'=>'updated_date',
            'label'=>'Added Datetime'
					],
                                           // 'survey_id',
                                           // 'id',
                           [
                'format' => 'raw',
                'value' => function ($model) {

                    return '<div class="edit-request-icon"><a data-id="'.$model->id.'" class="glyphicon glyphicon-menu-hamburger"></a></div>';
                },
				'label' => 'Start/View Details',
            ]

			],

            'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },


]);
	?>


  <?php
        Modal::begin([
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
            var url = '".Url::to(['customer-survey/create'])."';
          //  $('#interaction-view').load(url);
            $('#survey-widget tbody tr').removeClass('active-case-row');
            var modal_content = $('#customersurvey-modal').find('#customersurvey-modal-content');
            modal_content.html('').load( $(this).attr('value'), function() {
             //alert('ZZZZZZZZZZZZZZZZ');
                $('#customersurvey-modal').modal('show');
                btn.button('reset');
            });
        });




        $('#survey-widget tbody tr').click(function (e) {
               if($(this).hasClass('active-request-row'))
                return;
            $(this).siblings().removeClass('active-request-row');
            $(this).addClass('active-request-row');
          e.preventDefault();
            var thisRow = $(this);
            var id = $(this).data('id');
            var customer_id=$('#customer_id').val();
           // alert(id);
                   if(id != undefined) {
                var url = '".Url::to(['outbound-interaction/loadinteractions'])."?survey_response_id='+ id+'&customer_id='+customer_id;
                reloadInteractions(url);
            }

        });

  $('#survey-widget tbody .edit-request-icon a').click(function (e) {
            e.preventDefault();
            var thisRow = $(this);
            var crm_survey_response_id = $(this).data('id');
            var customer_id=$('#customer_id').val();
            var modal_content = $('#customersurvey-modal').find('#customersurvey-modal-content');
            if(crm_survey_response_id != undefined) {
                modal_content.html('').load( '".Url::to(['customer-survey/start'])."?crm_survey_response_id=' +crm_survey_response_id+'&customer_id='+customer_id, function() {
                    $('#customersurvey-modal').modal('show');
                });


            }
        });

    ");
    ?>
  <?php \yii\widgets\Pjax::end(); ?>
</div>
