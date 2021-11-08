<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\State;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Events';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="events-index">

    <h4><?= $sub_title ?></h4>


    <?php $form = ActiveForm::begin(['id' => 'dropdown-events-form']); ?>
    <table class="dropdown-form">
        <tr>
            <td><?= $form->field($model, 'event_name')->textInput(['maxlength' => 250, 'placeholder'=>'New Event'])->label(false)->error(false) ?></td>
            <td><?= $form->field($model, 'event_type')->textInput(['maxlength' => 50, 'placeholder'=>'Event Type'])->label(false)->error(false) ?></td>

        </tr>
        <tr>
          <td><?= $form->field($model, 'event_year')->textInput(['maxlength' => 4, 'placeholder'=>'Event Year'])->label(false)->error(false) ?></td>
          <td><?= $form->field($model, 'event_month')->textInput(['maxlength' => 12, 'placeholder'=>'Event Month'])->label(false)->error(false) ?></td>

        </tr>

        <tr>
          <td>
          <?= $form->field($model, 'event_location')->dropDownList(
                    ArrayHelper::map(State::find()->where('deleted != :id', ['id'=>1])->orderBy('name')->all(), 'id', 'name'),
                //    ['options' => [ 158 => ['selected ' => true]]],
                    ['prompt'=>'-----']
                );
            ?>
          </td>
        </tr>

        <tr>
          <td>
              <input type="hidden" name="hasNew" value="true">
              <?= Html::submitButton('<span class="glyphicon glyphicon-plus"></span> Add Event', ['class' => 'btn btn-success btn-sm','data-loading-text'=>'Adding...']) ?>
          </td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>



    <div style="clear: both;"></div>

    <div id="events-widget">
        <?= $this->render(Url::to('events_list'), [
                'dataProvider' => $dataProvider,
        ]) ?>
    </div>

</div>

<?php
    $this->registerJs("

        $('#dropdown-events-form').submit(function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            addDropdownValue($(this),'events');
            return false;
        });

    ");
?>
