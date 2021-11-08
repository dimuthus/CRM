<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

use frontend\models\interaction\InteractionCurrentOutcome;
use frontend\models\interaction\EscalationLevel;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Escalate To';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="escalated-to-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-escalated-to-form']); ?>

    <div class="form-group">
        <?= Html::dropDownList('interaction_outcome_id', null,
                ArrayHelper::map(InteractionCurrentOutcome::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
                ['prompt'=>'--- Select Interaction Outcome ---', 'class'=>'form-control', 'id'=>'interaction_outcome_id']
            ); 
        ?>
		
    </div>

    <?= $form->field($model, 'escalation_level_id')->widget(DepDrop::classname(), [
        'pluginOptions'=>[
            'depends'=>['interaction_outcome_id'],
            'placeholder'=>'--- Select Escalation Level ---',
            'url'=>Url::to(['/interaction/populateescalationlevel'])
        ]
    ])->label(false); ?>

    <div id="escalated-to-box" style="display:none">
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

        <div id="escalated-to-widget">
            <?= $this->render(Url::to('escalated_to_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#escalatedto-escalation_level_id').change(function (e) {
            if(!$(this).val()) {
                $('#escalated-to-box').hide();
                return;
            }
            $('#escalated-to-box').show();
            Loading('escalated-to-box',true);
            $.ajax({
              url: $('#dropdown-escalated-to-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'escalation_level_id':$(this).val()},
              success: function(response) {
                $('#escalated-to-widget').html(response); 
                Loading('escalated-to-box',true);
              }
            });
        });

        $('#dropdown-escalated-to-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
			//alert(form);
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
                        $('#escalated-to-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

