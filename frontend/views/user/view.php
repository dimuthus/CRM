<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */

$this->title = Yii::$app->name . ' - User Profile';

?>

<div class="profile-view">

    <h4>User Profile</h4>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-cog"></span> Change Password', ['changepassword'], ['class' => 'btn btn-sm btn-danger console-button']) ?>
    </p>

                                        
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

<?php
    $this->registerJs("

        $('#product-modal').find('#product-modal-title').html('".$this->title."');

        /*
            listener to handle click event of view customer details button
        */
        $('#update-product-button').click(function() {
            var btn = $(this);
            btn.button('loading');
            $('#product-modal').find('#product-modal-content')
                .load( $(this).attr('value'), function() {
                    $('#product-modal').modal('show');
                    btn.button('reset');
                });
        });
    ");
?>