<?php

use yii\helpers\Html;
use frontend\modules\tools\models\user\UserRole;
use mdm\admin\models\AuthItem;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'Permissions';
?>
<div class="auth-item-view">

 <div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">

    <div class="form-group">
        <?= Html::dropDownList('roles', null,
                ArrayHelper::map($roles->allModels, 'name', 'name'),
                ['prompt'=>'--- Select a Role ---', 'class'=>'form-control', 'id'=>'roles']
            ); 
        ?>
    </div>

    <div id="assignment-view" style="display:none">
        <?=
            $this->render('view', [
                'avaliable' => $avaliable,
                'assigned' => $assigned
            ]);
        ?>
    </div>

</div></div></div></div>

<?php
    $this->registerJs("

        $('#roles').change(function (e) {
            if(!$(this).val()) {
                $('#assignment-view').hide();
                return;
            }
            $('#assignment-view').show();
            Loading('assignment-view',true);

            $.ajax({
              type: 'get',
              data: {'refresh-widget':true, 'role-id':$(this).val()},
              success: function(response) {
                $('#assignment-view').html(response);
              }
            });
        });

    ");
?>
