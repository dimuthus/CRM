<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\complaint\ComplaintBrand;
use frontend\models\cases\OutcomeCode1;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Outcome Code 2';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="outcome-code2-index">

   <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">

    <?php $form = ActiveForm::begin(['id' => 'dropdown-outcome-code2-form']); ?>

    <?= $form->field($model, 'outcome_code1_id')->dropDownList(
            ArrayHelper::map(OutcomeCode1::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select Outcome Code 1 ---']
        )->label(false);
    ?>

    <div id="outcome-code2-box" style="display:none">
        <table class="dropdown-form">
            <tr>
                <td><?= $form->field($model, 'name')->textInput(['maxlength' => 250, 'placeholder'=>'New Item'])->label(false)->error(false) ?></td>
				<td><?= $form->field($model, 'inbound')->dropDownList(['1' => 'Inbound', '0' => 'Outbound'])->label(false)->error(false); ?></td>
                <td>
                    <input type="hidden" name="hasNew" value="true">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
                </td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>



        <div style="clear: both;"></div>

        <div id="outcome-code2-widget">
            <?= $this->render(Url::to('outcome_code2_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div></div></div></div>


<?php
    $this->registerJs("

        $('#outcomecode2-outcome_code1_id').change(function (e) {
            if(!$(this).val()) {
            alert('Im Here...');
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
