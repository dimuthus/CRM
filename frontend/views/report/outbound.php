<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::$app->name . ' -Inbound Interaction Data';
?>
<div class="complete-report-index">

  <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                Complete Outbound Report
            </h3>
        </div>
        <div class="panel-body">
    <table>
        <tr>
            <td>
                <?= $this->render(Url::to('outbound_filter'), [
                    'searchModel' => $searchModel,
                ]) ?>
            </td>
        </tr>
        <tr>
            <td id="report-generated" style="display:none">
                <?= $this->render(Url::to('outbound_result'), [
                    'dataProvider' => $dataProvider,
                ]) ?>
            </td>
        </tr>
    </table>

</div>
</div>
</div>
