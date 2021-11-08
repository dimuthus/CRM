
<style>
    .modal-dialog { width: 50%;
	height : 80%
	}
    
    .answerChk {
    margin-left: 50%;
    margin-top: -13px;
}
    </style>
    
 <?php

use yii\helpers\Html;

use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

use frontend\models\interaction\InteractionChannelType;
use frontend\models\interaction\InteractionReason;
use frontend\models\interaction\InteractionStatus;
use frontend\models\survey\SurveyResponse;
use frontend\models\survey\SurveyQuiz;
use frontend\models\survey\SurveyQuizSub;

/* @var $this yii\web\View */
/* @var $model frontend\models\interaction\Interaction */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Nestle Customer Satisfaction Survey';

?>

<div class="survey-form">

      <?= $this->render('_formsurvey', [
        'model' => $model, 
    ]) ?>

   
</div>
  <?php
    Modal::begin([
        'header' => '<h4 id="survey-modal-title"></h4>',
        'id' => 'survey-modal',
    ]);

    echo '<div id="survey-modal-content"></div>';

    Modal::end();
    ?>

        