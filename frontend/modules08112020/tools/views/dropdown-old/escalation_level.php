<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

use frontend\models\complaint\ComplaintBrand;
use frontend\models\cases\OutcomeCode1;
use frontend\models\interaction\InteractionCurrentOutcome;
use frontend\models\interaction\EscalationLevel;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Escalation Level';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="escalation-level-index">

    <h4 style="margin-bottom:30px"><?= $sub_title ?></h4>

    <?php $form = ActiveForm::begin(['id' => 'dropdown-escalation-level-form']); ?>

    <?= $form->field($model, 'interaction_outcome_id')->dropDownList(
            ArrayHelper::map(InteractionCurrentOutcome::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
            ['prompt'=>'--- Select Interaction Outcome ---']
        )->label(false);
    ?>

    <div id="escalation-level-box" style="display:none">
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

        <div id="escalation-level-widget">
            <?= $this->render(Url::to('escalation_level_list'), [
                    'dataProvider' => $dataProvider,
            ]) ?>
        </div>
    </div>

</div>

<?php
    $this->registerJs("

        $('#escalationlevel-interaction_outcome_id').change(function (e) {
            if(!$(this).val()) {
            alert('Im Here...');
                $('#escalation-level-box').hide();
                return;
            }
            $('#escalation-level-box').show();
            Loading('escalation-level-box',true);
            $.ajax({
              url: $('#dropdown-interaction-outcome-form').attr('action'),
              type: 'get',
              data: {'refresh-widget':true,'interaction_outcome_id':$(this).val()},
              success: function(response) {
                $('#escalation-level-widget').html(response); 
                Loading('escalation-level-box',true);
              }
            });
        });

        $('#dropdown-escalation-level-form').submit(function (e) {
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
                        $('#escalation-level-widget').html(response.body);
                        btn.button('reset');
                        form.find('input[type=text]').val('');
                    }    
                }
            });
        });

    ");
?>

