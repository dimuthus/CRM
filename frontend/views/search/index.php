<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\product\Product */

$this->title = Yii::$app->name . ' - Search';

?>
<div class="search-customer">

     <table class="layout-container">
        <tr>
            <?php if(Yii::$app->user->can('Search Contacts')) { ?>
                <td>
                    <?= $this->render('search_form', [
                        'searchModel' => $searchModel,
                    ]) ?>
                </td>
            <?php } ?>
<!--            <td>
            	< ?= $this->render('latest_cases', ['cases' => $cases,]) ?>
            </td>-->
        </tr>
        <?php if(Yii::$app->user->can('Search Contacts')) { ?>
            <tr>
                <td colspan="2" id="results_view">
                <?= $this->render('search_results', [
                        'results' => $results,
                    ]) ?>
                </td>
            </tr>
        <?php } ?>
    </table>

</div>
