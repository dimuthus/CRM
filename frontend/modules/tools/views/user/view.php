<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\tools\models\User */

$this->title = Yii::$app->name . ' - Tools';
$sub_title = 'User: '.$model->username;
?>
<div class="user-view">
<div class="panel panel-info" style="margin-top: 20px;">
        <div class="panel-heading">           
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-th"></span>
                <?= $sub_title ?>
            </h3>
        </div>
        <div class="panel-body">
    <p>
        <?php if(Yii::$app->user->can('Update User')) { ?>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary console-button']) ?>
        <?php } ?>
        <?php if(Yii::$app->user->can('Reset User Password')) { ?>
        <?= Html::a('<span class="glyphicon glyphicon-cog"></span> Reset Password', ['resetpassword', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger console-button',
            'data-confirm' => Yii::t('yii', 'Are you sure you want to reset the password for this user?'),]) ?>
        <?php } ?>
         <?php if(Yii::$app->user->can('Reset User Password')) { ?>
        <?= Html::a('<span class="glyphicon glyphicon-cog"></span> Unlock User', ['unlockuser', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger console-button',
            'data-confirm' => Yii::t('yii', 'Are you sure you want to unlock the user?'),]) ?>
        <?php } ?>
    </p>
    <div style="clear: both;"></div>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'first_name',
            'last_name',
            'email',
            'username',
            'role_id',
            'status.name',
            'creator.username',
            [
                'attribute'=>'creation_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
            [
                'attribute'=>'last_modified_datetime', 
                'format'=>['date', 'php:d M y @ h:i a']
            ],
        ],
    ]) ?>

</div>
    </div>
    </div>
