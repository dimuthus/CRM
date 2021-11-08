<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\request\RequestDeviceType;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Service Request Devices';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="request-device-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-request-device-form']); ?>

    <?= $form->field($model, 'type')->dropDownList(
            ArrayHelper::map(RequestDeviceType::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select a Type ---']
        )->label(false);
    ?>

    <div id="request-device-box" style="display:none">
        <table class="dropdown-form">
            <tr>
                <td><?= $form->field($model, 'model')->textInput(['maxlength' => 250, 'placeholder'=>'New Item'])->label(false)->error(false) ?></td>
                <td>
                    <input type="hidden" name="hasNew" value="true">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
                </td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>



        <div style="clear: both;"></div>

        <div id="request-device-widget">
            <?= $this->render(Url::to('request_device_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#requestdevice-type').change(function (e) {
            if(!$(this).val()) {
                $('#request-device-box').hide();
                return;
            }
            $('#request-device-box').show();
            Loading('request-device-box',true);
            $.ajax({
              url: $('#dropdown-request-device-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'type':$(this).val()},
              success: function(response) {
                $('#request-device-widget').html(response); 
                Loading('request-device-box',true);
              }
            });
        });

        $('#dropdown-request-device-form').submit(function (e) {
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
                        $('#request-device-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

