<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

use frontend\models\request\RequestType;
use frontend\models\request\RequestDetailType;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Service Request Additional Types';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="request-additional-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-request-additional-form']); ?>

    <div class="form-group">
        <?= Html::dropDownList('request_type', null,
                ArrayHelper::map(RequestType::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
                ['prompt'=>'--- Select a Type ---', 'class'=>'form-control', 'id'=>'request_type']
            ); 
        ?>
    </div>

    <?= $form->field($model, 'detail_type_id')->widget(DepDrop::classname(), [
        'pluginOptions'=>[
            'depends'=>['request_type'],
            'placeholder'=>'--- Select a Detail Type ---',
            'url'=>Url::to(['/request/populatedetails'])
        ]
    ])->label(false); ?>

    <div id="request-additional-box" style="display:none">
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

        <div id="request-additional-widget">
            <?= $this->render(Url::to('request_additional_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#requestadditionaltype-detail_type_id').change(function (e) {
            if(!$(this).val()) {
                $('#request-additional-box').hide();
                return;
            }
            $('#request-additional-box').show();
            Loading('request-additional-box',true);
            $.ajax({
              url: $('#dropdown-request-additional-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'type-id':$(this).val()},
              success: function(response) {
                $('#request-additional-widget').html(response); 
                Loading('request-additional-box',true);
              }
            });
        });

        $('#dropdown-request-additional-form').submit(function (e) {
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
                        $('#request-additional-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

