<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

use frontend\models\cases\ComplaintDivision;
use frontend\models\complaint\ComplaintBrand;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Complaint Sub Brand';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="complaint-sub-brand-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-complaint-sub-brand-form']); ?>

    <div class="form-group">
        <?= Html::dropDownList('division_id', null,
                ArrayHelper::map(ComplaintDivision::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
                ['prompt'=>'--- Select a Division ---', 'class'=>'form-control', 'id'=>'division_id']
            ); 
        ?>
    </div>

    <?= $form->field($model, 'brand_id')->widget(DepDrop::classname(), [
        'pluginOptions'=>[
            'depends'=>['division_id'],
            'placeholder'=>'--- Select a Brand ---',
            'url'=>Url::to(['/complaint/populatebrandddl'])
        ]
    ])->label(false); ?>

    <div id="complaint-sub-brand-box" style="display:none">
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

        <div id="complaint-sub-brand-widget">
            <?= $this->render(Url::to('complaint_sub_brand_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#complaintsubbrand-brand_id').change(function (e) {
            if(!$(this).val()) {
                $('#complaint-sub-brand-box').hide();
                return;
            }
            $('#complaint-sub-brand-box').show();
            Loading('complaint-sub-brand-box',true);
            $.ajax({
              url: $('#dropdown-complaint-sub-brand-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'brand_id':$(this).val()},
              success: function(response) {
                $('#complaint-sub-brand-widget').html(response); 
                Loading('complaint-sub-brand-box',true);
              }
            });
        });

        $('#dropdown-complaint-sub-brand-form').submit(function (e) {
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
                        $('#complaint-sub-brand-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

