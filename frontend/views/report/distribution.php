<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' -Distributed Customer Data';
?>
<div class="complete-report-index">

    <h4>Distributed Customer Data</h4>

    <table>
        <tr>
            <td>
                <?= $this->render(Url::to('distribution_filter'), [
                    'searchModel' => $searchModel,
                ]) ?>
            </td>
        </tr>
        <tr>
            <td id="report-generated" style="display:none">
                <?= $this->render(Url::to('distribution_result'), [
                    'dataProvider' => $dataProvider,
                ]) ?>
            </td>
        </tr>
    </table>

</div>
