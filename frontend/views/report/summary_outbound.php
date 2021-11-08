<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->name . ' - SUMMARY REPORT';
?>
<div class="">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
Outbound Summary Report            </h3>
        </div>
        <div class="panel-body">
            <table>
                <tr>
                    <td>
                        <?=
                        $this->render('summary_filter_outbound', [
                            'searchModel' => $searchModel,
                        ])
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?=
                        $this->render(Url::to('summary_result_outbound'), [
                            'dataProvider' => $dataProvider,
                        ])
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>