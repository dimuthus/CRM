<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\CustomerCampaign */

$this->title = "Customer Event: ".$model->id;
?>
<div class="customer-events-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'eventId.event_name',
            'eventType.event_type',
            'eventYear.event_year',
            'eventMonth.event_month',
            'createdBy.username',
        ],
    ]) ?>

    <?= DetailView::widget([
        'model' => $result,
        'attributes' => [
            'eventLocation.name',
        ],
    ]) ?>

</div>