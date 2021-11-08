<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\modules\survey\models\CrmSurveyQuestion;
use \yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sub_title = 'Survey Question Options List';
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
        <div id="survey-response-choice-widget">
       <?php Pjax::begin(['id' => 'complaint_brand','enablePushState'=>TRUE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

            <!--<div class="output" style="height: 300px; overflow-y: auto">-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'layout'=>"{items}\n{summary}\n{pager}",
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
                                                toggleDeleted(state, "'.Url::to(['/survey/survey-response-choice/update',
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
<!--                </div>-->
    <?php Pjax::end(); ?>
            </div>
 </div>
</div>
</div></div></div>