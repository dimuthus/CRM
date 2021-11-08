<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">
    
    <div class="alert alert-danger">
        
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <h2><?= Html::encode($this->title) ?></h2>
        <h3><?= nl2br(Html::encode($message)) ?></h3>
    </div>

</div>
