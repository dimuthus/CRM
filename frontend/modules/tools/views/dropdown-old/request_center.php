<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\Country;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Service Request Serice Centers';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="request-center-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-request-center-form']); ?>

    <?= $form->field($model, 'country_id')->dropDownList(
            ArrayHelper::map(Country::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select a Country ---']
        )->label(false); 
    ?>

    <div id="request-center-box" style="display:none">
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

        <div id="request-center-widget">
            <?= $this->render(Url::to('request_center_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#requestservicecenter-country_id').change(function (e) {
            if(!$(this).val()) {
                $('#request-center-box').hide();
                return;
            }
            $('#request-center-box').show();
            Loading('request-center-box',true);
            $.ajax({
              url: $('#dropdown-request-center-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'country-id':$(this).val()},
              success: function(response) {
                $('#request-center-widget').html(response); 
                Loading('request-center-box',true);
              }
            });
        });

        $('#dropdown-request-center-form').submit(function (e) {
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
                        $('#request-center-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

