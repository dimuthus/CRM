<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' - My Outbound Cases';
?>
<div class="requests-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                My Outbound Cases
            </h3>
        </div>
        <div class="panel-body">
            <table>
                <tr>
                    <td>
                        <?=
                        $this->render('myinbox_filter', [
                            'searchModel' => $searchModel,
                        ])
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?=
                        $this->render(Url::to('myinbox'), [
                            'dataProvider' => $dataProvider,
                        ])
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>