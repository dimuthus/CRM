<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\complaint\ComplaintBrand;
use frontend\models\cases\ComplaintDivision;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Complaint Brand';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="complaint-brand-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-complaint-brand-form']); ?>

    <?= $form->field($model, 'division_id')->dropDownList(
            ArrayHelper::map(ComplaintDivision::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select a Division ---']
        )->label(false);
    ?>

    <div id="complaint-brand-box" style="display:none">
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

        <div id="complaint-brand-widget">
            <?= $this->render(Url::to('complaint_brand_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#complaintbrand-division_id').change(function (e) {
            if(!$(this).val()) {
            alert('Im Here...');
                $('#complaint-brand-box').hide();
                return;
            }
            $('#complaint-brand-box').show();
            Loading('complaint-brand-box',true);
            $.ajax({
              url: $('#dropdown-complaint-brand-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'division_id':$(this).val()},
              success: function(response) {
                $('#complaint-brand-widget').html(response); 
                Loading('complaint-brand-box',true);
              }
            });
        });

        $('#dropdown-complaint-brand-form').submit(function (e) {
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
                        $('#complaint-brand-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

