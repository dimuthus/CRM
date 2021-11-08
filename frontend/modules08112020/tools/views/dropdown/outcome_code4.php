<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

use frontend\models\inboundInteraction\OutcomeCode1;
use frontend\models\inboundInteraction\OutcomeCode2;
use frontend\models\inboundInteraction\OutcomeCode3;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Outcome Code 4';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="outcome-code4-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-outcome-code4-form']); ?>

    <div class="form-group">
        
<label> Outcomecode1 </label>
        <?= Html::dropDownList('outcome_code1', 'null',
                ArrayHelper::map(OutcomeCode1::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
                ['prompt'=>'--- Select Outcome Code 1 ---', 'class'=>'form-control', 'id'=>'outcome_code1','label'=>'Outcomecode1'],['label'=>'puka']
            );
        ?>
    </div>

    <?= $form->field($model, 'outcome_code2_id')->widget(DepDrop::classname(), [
          //      'data'=> ArrayHelper::map(RequestDetailType::find()->where('deleted != :id and type_id=:type', ['id'=>1,'type'=>$model->service_request_type])->orderBy('name')->all(), 'id', 'name'),

        'pluginOptions'=>[
            'depends'=>['outcome_code1'],
            'placeholder'=>'--- Select Outcome Code 21 ---',
            'url'=>Url::to(['/cases/populateoc2'])
        ]
    ])->label('Outcomecode2'); ?>
    
    
        <?= $form->field($model, 'outcome_code3_id')->widget(DepDrop::classname(), [
            // 'data'=> ArrayHelper::map(OutcomeCode3::find()->where('deleted !=id', ['id'=>1,'outcome_code2_id'=>$model->outcome_code2_id])->orderBy('name')->all(), 'id', 'name'),
        'pluginOptions'=>[
            'depends'=>['outcomecode4-outcome_code2_id'],
            'placeholder'=>'--- Select Outcome Code 3 ---',
            'url'=>Url::to(['/cases/populateoc3'])
        ]
    ])->label('Outcomecode3'); ?>

    <div id="outcome-code4-box" style="display:none">
        <table class="dropdown-form">
            <tr>
                <td><?= $form->field($model, 'name')->textInput(['maxlength' => 250, 'placeholder'=>'New Item'])->label(false)->error(false) ?></td>
                <td>
                    <input type="hidden" name="hasNew" value="true">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
                </td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>



        <div style="clear: both;"></div>

        <div id="outcome-code4-widget">
            <?= $this->render(Url::to('outcome_code4_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>
<?php
    $this->registerJs("

        $('#outcomecode4-outcome_code3_id').change(function (e) {
            if(!$(this).val()) {
                $('#outcome-code4-box').hide();
                return;
            }
            $('#outcome-code4-box').show();
            Loading('outcome-code4-box',true);
            $.ajax({
              url: $('#dropdown-outcome-code4-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'outcome_code3_id':$(this).val()},
              success: function(response) {
                $('#outcome-code4-widget').html(response);
                Loading('outcome-code4-box',true);
              }
            });
        });

        $('#dropdown-outcome-code4-form').submit(function (e) {
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
                        $('#outcome-code4-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }
                }
            });
        });

    ");
?>


