<?php

use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\editable\Editable;
use kartik\widgets\SwitchInput;

?>

<?php \yii\widgets\Pjax::begin(['id' => 'interaction_outcome','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

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
                'attribute'=>'name', 
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
                'attribute' => 'deleted',
                'format' => 'raw',
                'contentOptions'=>['class'=>'switch'],
                'value' => function ($model) {   

                        $switch = SwitchInput::widget([
                                    'name'=>'deleted',
                                    'value'=>($model->deleted == 0)?1:0,
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
                                                toggleDeleted(state, "'.Url::to(['/tools/dropdown/interaction-outcome',
                                                                                        'id' => $model->id,
                                                                                        'hasToggle'=>true
                                                                                        ]).'"); 
                                            }',
                                    ]
                                ]);

                        return $switch;
                },
            ]
        ]
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>