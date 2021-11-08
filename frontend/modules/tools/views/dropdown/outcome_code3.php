<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

use frontend\models\cases\OutcomeCode1;
use frontend\models\cases\OutcomeCode2;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Outcome Code 3';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="outcome-code3-index">

  <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">

    <?php $form = ActiveForm::begin(['id' => 'dropdown-outcome-code3-form']); ?>

    <div class="form-group">
        <?= Html::dropDownList('outcome_code1', null,
                ArrayHelper::map(OutcomeCode1::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
                ['prompt'=>'--- Select Outcome Code 1 ---', 'class'=>'form-control', 'id'=>'outcome_code1']
            );
        ?>
    </div>

    <?= $form->field($model, 'outcome_code2_id')->widget(DepDrop::classname(), [
        'pluginOptions'=>[
            'depends'=>['outcome_code1'],
            'placeholder'=>'--- Select Outcome Code 2 ---',
            'url'=>Url::to(['/cases/populateoc2'])
        ]
    ])->label(false); ?>

    <div id="outcome-code3-box" style="display:none">
        <table class="dropdown-form">
            <tr>
                <td><?= $form->field($model, 'name')->textInput(['maxlength' => 250, 'placeholder'=>'New Item'])->label(false)->error(false) ?></td>
                <td>
				<td><?= $form->field($model, 'inbound')->dropDownList(['1' => 'Inbound', '0' => 'Outbound'])->label(false)->error(false); ?></td>
                    <input type="hidden" name="hasNew" value="true">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
                </td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>



        <div style="clear: both;"></div>

        <div id="outcome-code3-widget">
            <?= $this->render(Url::to('outcome_code3_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div></div></div></div>

<?php
    $this->registerJs("

        $('#outcomecode3-outcome_code2_id').change(function (e) {
            if(!$(this).val()) {
                $('#outcome-code3-box').hide();
                return;
            }
            $('#outcome-code3-box').show();
            Loading('outcome-code3-box',true);
            $.ajax({
              url: $('#dropdown-outcome-code3-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'outcome_code2_id':$(this).val()},
              success: function(response) {
                $('#outcome-code3-widget').html(response);
                Loading('outcome-code3-box',true);
              }
            });
        });

        $('#dropdown-outcome-code3-form').submit(function (e) {
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
                        $('#outcome-code3-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }
                }
            });
        });

    ");
?>

