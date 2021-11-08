<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\campaign\Campaign;
use frontend\modules\tools\models\user\User;
?>

<?php \yii\widgets\Pjax::begin(['id' => 'distributed_list','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>
		
	<div id="no-of-customers"><h5>No of customers available to distribute: <?= $noofcustomers ?><h5/></div>
		
	
<?php \yii\widgets\Pjax::end(); ?>


