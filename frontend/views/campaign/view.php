<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\Campaign */

$this->title = "Campaign: ".$model->id;
?>
<div class="campaign-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'id',
            'name',
            'start_date',
            'end_date',
            'CriteriaLabel',
            'createdBy.username',
            'created_datetime',
            'lastUpdatedBy.username',
            'last_updated_datetime',
        ],
    ]) ?>

</div>
