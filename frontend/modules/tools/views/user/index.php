<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Users';
/*$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="user-index">
<div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Add User', ['create'], ['class' => 'btn btn-sm btn-success console-button']) ?>
    </p>
    <div style="clear: both;"></div>
    <?php \yii\widgets\Pjax::begin(['enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		
        'layout'=>"{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'full_name',
            'email',
            'role_id',
            'status.name'
        ],
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
    ]); ?>

  

    <?php \yii\widgets\Pjax::end(); ?>
  <?php
    $this->registerJs("

        $('td').click(function (e) {
            var id = $(this).closest('tr').data('id');
            if(e.target == this && id != undefined)
                location.href = '" . Url::to(['/tools/user']) . "/' + id;
        });
		$('.table').DataTable({
		
		dom: 'Bfrtip',
		buttons: [
			'copy', 'csv', 'excel'
		]
	});
    ");
    ?>
</div>
    </div>
    </div>

