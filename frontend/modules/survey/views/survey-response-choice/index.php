<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurveyQuestion;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Survey Question Option List';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crm-survey-response-choice-index">

     <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">

    <p>
          <?php $form = ActiveForm::begin(); ?>

   <?= $form->field($model, 'question_id')->dropDownList(
            ArrayHelper::map(CrmSurveyQuestion::find()->where('is_deleted != :id', ['id'=>1])->orderBy('id')->all(), 'id', 'text'),
            ['prompt'=>'--- Select Question Text ---']
        )->label(true);
    ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
      <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </p>
<?php \yii\widgets\Pjax::begin(['id' => 'complaint_brand','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
         'layout'=>"{items}\n{summary}\n{pager}",
        'options'=>[
            'class'=>'editable-grid',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
                  [
                'class' => 'kartik\grid\EditableColumn',
                'attribute'=>'text',
                'editableOptions' => [
                    'inputType' => Editable::INPUT_TEXT,
                    'asPopover' => false,
                    'inlineSettings' => [
                        'templateBefore' =>'<div class="kv-editable-form-inline"><div class="form-group"></div>',
                        'templateAfter' =>'<div class="form-group">{buttons}{close}</div></div>'
                    ],
                    'options' => [
                        'pluginOptions' => ['min'=>0, 'max'=>250]
                    ]
                ],
                'pageSummary' => true
            ],
             [
                'attribute' => 'is_deleted',
                'format' => 'raw',
                'contentOptions'=>['class'=>'switch'],
                'value' => function ($model) {

                        $switch = SwitchInput::widget([
                                    'name'=>'is_deleted',
                                    'value'=>($model->is_deleted == 0)?1:0,
                                    'pluginOptions'=>[
                                        'handleWidth'=>40,
                                        'size'=>'mini',
                                        'offColor' => 'danger',
                                        'onText'=>'Active',
                                        'offText'=>'Inactive',
                                     ],
                                    'pluginEvents'=> [
                                        'switchChange.bootstrapSwitch' =>
                                            'function(event, state) {
                                                toggleDeleted(state, "'.Url::to(['/survey/surveyresponsechoice/update',
                                                                                        'id' => $model->id,
                                                                                        'hasToggle'=>true
                                                                                        ]).'");
                                            }',
                                    ]
                                ]);

                        return $switch;
                },
            ]
           // 'id',
           // 'question_id',
           // 'text:ntext',
           // 'is_deleted',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div></div>
</div>
</div>

<?php
    $this->registerJs("

        $('#outcomecode2-outcome_code1_id').change(function (e) {
            if(!$(this).val()) {
            //alert('Im Here...');
                $('#outcome-code2-box').hide();
                return;
            }
            $('#outcome-code2-box').show();
            Loading('outcome-code2-box',true);
            $.ajax({
              url: $('#dropdown-outcome-code2-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'outcome_code1_id':$(this).val()},
              success: function(response) {
                $('#outcome-code2-widget').html(response);
                Loading('outcome-code2-box',true);
              }
            });
        });

        $('#dropdown-outcome-code2-form').submit(function (e) {
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
                        $('#outcome-code2-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }
                }
            });
        });

    ");
?>
