<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;
use kartik\widgets\DepDrop;

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurveyQuestion;
use frontend\modules\survey\models\CrmSurveyQuestionType;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Question Options';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-response-choice-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
    <?php $form = ActiveForm::begin(['id' => 'survey-response-choice-form']); ?>
    <?= $form->field($model, 'question_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestion::find()->where('question_type_id!=3 and is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'text'),
                ['prompt'=>'--- Select question ---', 'class'=>'form-control', 'id'=>'question_id']
        )->label(true);
    ?>

         <?= $form->field($model, 'question_type_id')->widget(DepDrop::classname(), [
        'pluginOptions'=>[
            'depends'=>['question_id'],
            'placeholder'=>'--- Select question type ---',
            'url'=>Url::to(['/survey/survey-response-choice/populateqtype'])
        ]
    ])->label(false); ?>
    <div id="survey-response-choice-box" style="display:none">
  <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>


    <div class="form-group">
      <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </p>


        <div style="clear: both;"></div>

      <div id="survey-response-choice-widget">
            <?= $this->render(Url::to('response_choice_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
            </div>
 </div>
</div>
<?php
    $this->registerJs("

        $('#crmsurveyresponsechoice-question_type_id').change(function (e) {
      //// alert('sss');
              var qtTxt=$('#crmsurveyresponsechoice-question_type_id :selected').text();
              var qVal=$('#question_id').val();
            //alert(qtTxt+qVal);
            if(!$(this).val()) {
            //alert('Im Here...');
                $('#survey-response-choice-box').hide();
                return;
            }
         $('#survey-response-choice-box').show();
              Loading('survey-response-choice-box',true);
             $.ajax({
              url: $('#survey-response-choice-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'question_id':$(this).val()},
              success: function(response) {

                $('#survey-response-choice-widget').html(response);
                Loading('survey-response-choice-box',true);
              }
            });
        });

        $('#survey-response-choice-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            if(isFormEmpty(form))
                return;
            var btn = form.find(':submit');
            btn.button('loading');
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if(!response.hasError) {
                        $('#survey-response-choice-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }
                }
            });
        });



    ");
?>
