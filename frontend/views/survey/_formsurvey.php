
<style>
    .modal-dialog { width: 50%;
	height : 80%
	}
    
    .answerChk {
   
   
}
	.detractors{
		display: inline-block;
		width: 60px;
		height: 60px;
		padding: 5px;
		border: 1px solid blue;        
		background-color: red; 
		color:white;
	}
	.passive{
		display: inline-block;
		width: 60px;
		height: 60px;
		padding: 5px;
		border: 1px solid blue;        
		background-color: yellow; 
	}
	.promoters{
		display: inline-block;
		width: 60px;
		height: 60px;
		padding: 5px;
		border: 1px solid blue;        
		background-color: green; 
		color:white;
	}
	.interaction-form{
		height:1000px;
	}
    </style>
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

use frontend\models\interaction\InteractionChannelType;
use frontend\models\interaction\InteractionReason;
use frontend\models\interaction\InteractionStatus;

/* @var $this yii\web\View */
/* @var $model frontend\models\interaction\Interaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="survey-form">
 <h4>Survey</h4>
  <?php 
  
  $form = ActiveForm::begin([
        'action' => ['survey/createsurvey?case_id='.$model->request_id],'options' => ['method' => 'post'],
        'layout' => 'horizontal',
        'id' => 'survey-form',
        'errorSummaryCssClass' => 'alert alert-danger',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{hint}\n{endWrapper}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-3 col-sm-left-align',
                'offset' => 'col-sm-offset-1',
                'wrapper' => 'col-sm-8 error-enabled-custom-field',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>
	
	<?= Html::activeHiddenInput($model, 'request_id') ;?>
   <?php
    $surveyQuizQry = Yii::$app->db->createCommand("SELECT `id`, `quiz_text`,`answer_type`,sub_option FROM `survey_quiz` WHERE is_deleted=0")->queryAll();
        $surveyDisplay="<div>";
        $i=0;
        foreach ($surveyQuizQry as $key => $value) {
            $i++;
            $quizId=$value['id'];
            $zerotoTen="";
            $yesNo="";
            $multiple="";
            $freeText="";
            $answer_type=$value['answer_type'];
            $sub_option=$value['sub_option'];
            $options="";
            //if ($sub_option==0){
            switch ($answer_type) {
            case "ZeroToTen":
                $optionsA="";
                for ($x = 1; $x <= 10; $x++) {
					if($x <=6){
						$optionsA .="<div class= \"detractors\"><input required type=\"radio\" id=\"rbZerotoTen\"  value=".$x." name=".'radioQuiz_'.$quizId.">  $x </div> ";
					}
					else if( $x >= 7 && $x <= 8){
						$optionsA .="<div class= \"passive\"><input required type=\"radio\" id=\"rbZerotoTen\"  value=".$x." name=".'radioQuiz_'.$quizId.">$x </div>";
					}
					else{
						$optionsA .="<div class= \"promoters\"><input required type=\"radio\" id=\"rbZerotoTen\"  value=".$x." name=".'radioQuiz_'.$quizId.">$x </div> ";
					}
                }
                $options .="<div style='text-align:left;'>".$optionsA."</div>";
                break;
			case "ZeroToFive":
                $optionsA="";
                for ($x = 1; $x <= 4; $x++) {
					if($x ==1){
						$optionsA .="<input required type=\"radio\" id=\"rbZerotoTen\" value=".$x." name=".'radioQuiz_'.$quizId.">Pending Parts<br>";
					}
					else if($x == 2){
						$optionsA .="<input required type=\"radio\" id=\"rbZerotoTen\" value=".$x." name=".'radioQuiz_'.$quizId.">Unable to resolve issue<br>";
					}
					else if($x == 3){
						$optionsA .="<input required type=\"radio\" id=\"rbZerotoTen\" value=".$x." name=".'radioQuiz_'.$quizId.">Request for Machine replacement<br>";
					}
					else{
				//		$optionsA .="<input type=\"radio\" id=\"rbZerotoTen\" value=".$x." name=".'radioQuiz_'.$quizId."><input type='text' name='txtboxQuiz_".$quizId."'/>Add option<br>";
					$optionsA .="<input required type=\"radio\" id=\"rbZerotoTen\" value=".$x." name=".'radioQuiz_'.$quizId.">Other";
					}
               // $optionsA .="<input required type=\"radio\" id=\"rbZerotoTen\" value=".$x." name=".'radioQuiz_'.$quizId.">$x";
                }
                $options .="<div style='align:center;'>".$optionsA."</div>";
                break;
            case "Multiple":
                
                break;
            case "YesNo":
                $options .="<br/><div class='answerChk' style='align:left;'><input required type=\"radio\" id=\"rbYesNo\" value=\"YES\" name=".'radioQuiz_'.$quizId."> YES <input type=\"radio\" id=\"rbYesNo\" value=\"NO\" name=".'radioQuiz_'.$quizId."> NO </div>";
                break;
             case "FreeText":
               $options .="<br><div><input required  type='text' name='txtboxQuiz_".$quizId."'/></div>";
                break;
            case "LQ":
               $options .="";
                break;
            default:
                echo "Your favorite color is neither red, blue, nor green!";
           }
            
            //}
           // $zerotoTen="<td><input type=\"radio\" id=\"radioButton\"></td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td>";
            
           
            $qry2="SELECT `id`,related_quiz_id, `quiz_text`,`answer_type` FROM `survey_quiz_sub` WHERE is_deleted=0 AND related_quiz_id=".$quizId."" ;
            $surveyQuizQrySub = Yii::$app->db->createCommand($qry2)->queryAll();
            $rowCount=count($surveyQuizQrySub);
			
			$optionsSub="";
            if ($rowCount>0){
            

            foreach ($surveyQuizQrySub as $key => $value2) {
                     $answer_type_Sp=$value2['answer_type'];
                     $subOptionId=$value2['id'];
                     $related_quiz_id=$value2['related_quiz_id'];
                     //echo $related_quiz_id."<br/>";
                     $optionsSubA="";
                   switch ($answer_type_Sp) {
                        case "ZeroToTen":
                            for ($y = 1; $y <= 10; $y++) {
                            $optionsSubA .="<input checked required type=\"radio\"  value=".$y." name=".'radioQuiz_'.$related_quiz_id.">".$y;
                            }
                            $optionsSub .="<div>".$value2['quiz_text']."</div><div class='answerChk'>".$optionsSubA."</div>";
                            break;
						case "ZeroToFive":
                            for ($x = 1; $x <= 5; $x++) {
                            $optionsSubA .="<input checked required type=\"radio\"  value=".$x." name=".'radioQuiz_'.$related_quiz_id.">".$x;
                            }
                            $optionsSub .="<div>".$value2['quiz_text']."</div><div class='answerChk'>".$optionsSubA."</div>";
                            break;
                        case "Multiple":
                            $optionsSub .="<div>".$value2['quiz_text']."</div><div class='answerChk'><input checked type=\"checkbox\" name=".'checkBox_'.$related_quiz_id.'_'.$subOptionId." value=".$quizId."></div>";
                            break;
                        case "YesNo":
                         //   if ($rowCount<>0){
                            $optionsSub .="<div>".$value2['quiz_text']."</div><div class='answerChk'><input checked required type=\"radio\" id=\"rbYesNo\" value=\"YES\" name=".'radioQuiz_'.$related_quiz_id.'_'.$subOptionId.">YES<input type=\"radio\" id=\"rbYesNo\" value=\"NO\" name=".'radioQuiz_'.$related_quiz_id.'_'.$subOptionId.">NO</div>";
                         /*   }
                            else{
                               $optionsSub ="";
                            }*/
                            break;
                         case "FreeText":
                           $optionsSub .="<div>".$value2['quiz_text']."</div><br/><div><input required type='text' name='txtboxQuiz_".$related_quiz_id.'_'.$subOptionId."'/></div>";
                            break;

                        default:
                            echo "Your favorite color is neither red, blue, nor green!";
                    }
                    }
            }
           $surveyDisplay .="<div id=".$i."><div>".$i.")  ".$value['quiz_text']."</div>".$options."</div><br>".$optionsSub."<hr/>" ;
          // $surveyDisplaySub .="<div id=".$i."><div>".$i.")".$value2['quiz_text']."</div>".$optionsSub."</div>" ;        
          
            
             
         }
        // $surveyDisplay."</table>";
         //$surveyDisplay="<table><tr><td>mytd</td></br><td>mytd</td></br><td>mytd</td></br><td>mytd</td></br><td>mytd</td></br><td>mytd</td></br><td>mytd</td></tr></table>";
         // $surveyDisplay1='testttttAAAAABBBBBBBCCBBBBtttqqqqqq';
        echo  $surveyDisplay."<div class=\"col-sm-offset-5\"><input type=\"submit\" class=\"btn btn-info\" value=\"Submit Button\" id='mypuka'/></div>";


 ?>
       

 

    <?php ActiveForm::end(); ?>

</div>
 <?php
$this->registerJs("

$('#survey-form').submit(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
		var case_id = $('#surveyresponse-request_id').val();
		//alert('case_id : '+ case_id);
	
        saveSurvey($(this), case_id);
        return false;
    });


");
?>