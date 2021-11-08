<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\interaction\Interaction */

$this->title = "Interaction: ".$model->outbound_interaction_id;
?>
<div class="interaction-view">

	<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'outbound_interaction_id',
            ['attribute'=>   'interactionStatus.name','label'=>'Interaction Status'],
            ['attribute'=>    'channelType.name','label'=>'Channel Type'],
			'notes',
            ['attribute'=>   'createdBy.username','label'=>'Created By'],
			
             'created_datetime',
        ],
    ]) ?>

</div>

<?php
    $this->registerJs("

        $('#interaction-modal').find('#interaction-modal-title').html('".$this->title."');

    ");
?>
