<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' -Customer Events Data';
?>
<div class="complete-report-index">

    <h4>Customer Events Data</h4>

    <table>
        <tr>
            <td>
                <?= $this->render(Url::to('events_filter'), [
                    'searchModel' => $searchModel,
                ]) ?>
            </td>
        </tr>
        <tr>
            <td id="report-generated" style="display:none">
                <?= $this->render(Url::to('events_result'), [
                    'dataProvider' => $dataProvider,
                ]) ?>
            </td>
        </tr>
    </table>

</div>
