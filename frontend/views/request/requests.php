<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' - Service Requests';
?>
<div class="requests-index">

    <h4>Service Requests</h4>

    <table>
        <tr>
            <td>
                <?= $this->render('request_filter', [
                    'searchModel' => $searchModel,
                ]) ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $this->render(Url::to('request_list'), [
                    'dataProvider' => $dataProvider,
                ]) ?>
            </td>
        </tr>
    </table>

</div>
