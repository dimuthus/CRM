<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\campaign\CustomerCampaign */

$this->title = 'Assign Customer to New Campaign';

?>
<div class="customer-campaign-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
