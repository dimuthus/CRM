<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\request\RequestType;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Service Request Detail Types';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="request-detail-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-request-detail-form']); ?>

    <?= $form->field($model, 'type_id')->dropDownList(
            ArrayHelper::map(RequestType::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select a Type ---']
        )->label(false); 
    ?>

    <div id="request-detail-box" style="display:none">
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

        <div id="request-detail-widget">
            <?= $this->render(Url::to('request_detail_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#requestdetailtype-type_id').change(function (e) {
            if(!$(this).val()) {
                $('#request-detail-box').hide();
                return;
            }
            $('#request-detail-box').show();
            Loading('request-detail-box',true);
            $.ajax({
              url: $('#dropdown-request-detail-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'type-id':$(this).val()},
              success: function(response) {
                $('#request-detail-widget').html(response); 
                Loading('request-detail-box',true);
              }
            });
        });

        $('#dropdown-request-detail-form').submit(function (e) {
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
                        $('#request-detail-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

