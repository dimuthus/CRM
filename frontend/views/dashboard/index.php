<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' - Dashboard';
?>
<div class="dashboard-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                Dashboard
            </h3>
        </div>
        <div class="panel-body">
            <table>
                <tr>
                    <td>
                        <?=
                        $this->render(Url::to('dashboard_filter'), [
                            'searchModel' => $searchModel,
                        ])
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?=
                        $this->render(Url::to('dashboard'), [
                            'dataProvider' => $dataProvider,
                            'summary' => $summary,
                        ])
                        ?>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>
