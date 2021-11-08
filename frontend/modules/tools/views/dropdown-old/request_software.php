<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\request\RequestSoftwareCategory;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Service Request Software Services';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="request-software-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-request-software-form']); ?>

    <?= $form->field($model, 'category')->dropDownList(
            ArrayHelper::map(RequestSoftwareCategory::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select a Type ---']
        )->label(false);
    ?>

    <div id="request-software-box" style="display:none">
        <table class="dropdown-form">
            <tr>
                <td><?= $form->field($model, 'group')->textInput(['maxlength' => 250, 'placeholder'=>'Material'])->label(false)->error(false) ?></td>
                <td><?= $form->field($model, 'description')->textInput(['maxlength' => 250, 'placeholder'=>'Description', 'style'=>'width:450px'])->label(false)->error(false) ?></td>
                <td>
                    <input type="hidden" name="hasNew" value="true">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
                </td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>



        <div style="clear: both;"></div>

        <div id="request-software-widget">
            <?= $this->render(Url::to('request_software_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#requestsoftware-category').change(function (e) {
            if(!$(this).val()) {
                $('#request-software-box').hide();
                return;
            }
            $('#request-software-box').show();
            Loading('request-software-box',true);
            $.ajax({
              url: $('#dropdown-request-software-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'category':$(this).val()},
              success: function(response) {
                $('#request-software-widget').html(response); 
                Loading('request-software-box',true);
              }
            });
        });

        $('#dropdown-request-software-form').submit(function (e) {
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
                        $('#request-software-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

