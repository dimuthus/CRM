<style>

    .answerChk {
   /*float: left;*/
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
?>

<h4> Start the survey</h4>
<?php



  $form = ActiveForm::begin([
 'action' => ['customer-survey/savesurvey?crm_survey_response_id='.$crm_survey_response_id.'&customer_id='.$customer_id.'&case_id='.$case_id],'options' => ['method' => 'post'],
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
<?php
//echo "SURVEY NUMBER".$survey_id. "CUSTOMER".$customer_id."<br/>";

$surveyDetails="SELECT
    `campaign`.`name`
    , `customer`.`full_name`
    , `crm_survey_response`.`started_at`
    , `crm_survey_response`.`completed_at`
    ,`crm_survey_response`.`survey_id`

FROM
    `crm_survey_response`
    LEFT JOIN `campaign`
        ON (`crm_survey_response`.`survey_id` = `campaign`.`id`)
    LEFT JOIN `customer`
        ON (`crm_survey_response`.`respondent_id` = `customer`.`id`) WHERE `crm_survey_response`.`survey_id`=$crm_survey_response_id And `crm_survey_response`.`respondent_id` = $customer_id ";

$surveyRes = Yii::$app->db->createCommand($surveyDetails)->queryOne();
$comp_at= "SELECT completed_at from `crm_survey_response` WHERE `crm_survey_response`.`survey_id`=$crm_survey_response_id And `crm_survey_response`.`respondent_id` = $customer_id ";

$completed_at= Yii::$app->db->createCommand($comp_at)->queryOne();
// var_dump($completed_at);
// var_dump($completed_at['completed_at']);
// var_dump(is_null($completed_at['completed_at']));
// die();
// die();

$survey_id=$_GET['crm_survey_response_id'];

// var_dump($_GET);
// die();
?>
<div  class="alert alert-danger">
    <div><strong>Survey Name : <?= $surveyRes['name'];?><input type="hidden" name='survey_id' value='<?= $survey_id;?>'><br/>
     Customer Name : <?= $surveyRes['full_name'];?><br/>
     Started Date :    <?= $surveyRes['started_at'];?></strong></div>
     Completed Date : <?= $surveyRes['completed_at'];?></strong></div>
</div>
<?php
$surveyQuiz = Yii::$app->db->createCommand("SELECT
    `crm_survey_question_order`.`servey_id`
    , `crm_survey_question_order`.`question_id`
    , `crm_survey_question`.`text`
    , `crm_survey_question_order`.`order`
    , `crm_survey_question`.`question_type_id`
FROM
    `crm_survey_question_order`
    INNER JOIN `crm_survey_question`
        ON (`crm_survey_question_order`.`question_id` = `crm_survey_question`.`id`)
        INNER JOIN `crm_survey_response`
        ON (`crm_survey_response`.`respondent_id` = $customer_id And `crm_survey_response`.`survey_id` = $survey_id )
        WHERE
          `crm_survey_question_order`.`servey_id`=$survey_id ORDER BY    `crm_survey_question_order`.`order`")->queryAll();

$i=0;
foreach ($surveyQuiz as $key => $value) {
            $i++;
            $qId= $value['question_id'];
            $qType=$value['question_type_id'];
            echo $i.") ".$value['text']."<br>";
           // answer for quiz
             /*
        1  MCQ-single-choice
     2  MCQ-multiple-choice
     3  Keyin-choice
                                */
           $radio="";
           $optionsA="";
		   $otherCommentTxtbox="";
		   $other_comment="";
           if ($qType !=3){
                $surveyAnsChoice = Yii::$app->db->createCommand("SELECT id,text FROM crm_survey_response_choice WHERE question_id=$qId AND  is_deleted=0")->queryAll();
            foreach ( $surveyAnsChoice as $key=>$ACvalue){
                $ansText=$ACvalue['text'];
                $ansId=$ACvalue['id'];

                //qry result
                $chkAnswerOption="";
                if ( $qType!=3){
                     $chkAnswerOption=" AND answer= $ansId " ;
                }
                $surveyResultQry=" SELECT answer,other_comment FROM crm_survey_result WHERE survey_response_id=$crm_survey_response_id AND question_id= $qId $chkAnswerOption AND respondent_id= $customer_id AND case_id= $case_id $chkAnswerOption";
                $surveyResultChoice = Yii::$app->db->createCommand($surveyResultQry)->queryAll();
                $checked="";
                $answers="";

            foreach ( $surveyResultChoice as $key=>$ReultValue){
                $answers=$ReultValue['answer'];


				$other_comment=$ReultValue['other_comment'];
                if($answers==$ansId){
                   $checked="checked";
                }
            }
             if ($qType==1){
              // 	$optionsA .="<div class='answerChk'><input required type=\"radio\" id=\"rbZerotoTen\"  value=".$qId.'_'.$ansId." name=".'radioQuiz_'.$qId.'_'.$ansId.">  ".$ansText."</div><br/> ";
				$haystack = $ansText;
				$needle   = "other comments";
        
				$otherCommentTxtbox="";
				if( strpos( $haystack, $needle ) !== false) {
					$otherCommentTxtbox="<br/>(Please Specify)<input type='text' id='otherComment' name='radioOptionOtherComment_".$qId."' value=$other_comment></input>";
				}
              $optionsA .="<div class='answerChk'><input   $checked  required type=\"radio\"   value=".$ansId." name='radioOption_".$qId."'>  ".$ansText.$otherCommentTxtbox."</div><br/> ";

            }
            else  if ($qType==2){

                 // $optionsA .="<div class='answerChk'><input checked type=\"checkbox\" name=".'checkBox_'.$qId.'_'.$ansId." value=".$qId.'_'.$ansId.">  ".$ansText."</div><br/>";
                            $optionsA .="<div class='answerChk'><input $checked type=\"checkbox\" name='checkBoxOption_".$qId."[]' value=".$ansId.">  ".$ansText."</div><br/>";


            }



            }
           }
           else{
                $surveyResultQry=" SELECT answer FROM crm_survey_result WHERE survey_response_id=$crm_survey_response_id AND question_id= $qId AND respondent_id= $customer_id AND case_id= $case_id";
                $surveyResultChoiceTxt = Yii::$app->db->createCommand($surveyResultQry)->queryOne();

                $answers=$surveyResultChoiceTxt['answer'];
                $answerid= (int)$answers;
                $query= "select text from `crm_survey_response_choice` where id = $answerid";
                $q6answer=Yii::$app->db->createCommand($query)->queryOne();
                $anstext= $q6answer['text'];
                
                

                
                 $optionsA .="<div class='answerChk'><textarea style=\"width:100%;\" type=\"text\" name=".'textOption_'.$qId." value='$anstext'>$anstext</textarea> </div><br/>";

           }

             echo "<div style='background-color: lightblue;'>".$optionsA."</div>";

            echo "<hr/>";
        }
        if(is_null($completed_at['completed_at'])){
        echo "<div class=\"col-sm-offset-5\"><input type=\"submit\" class=\"btn btn-info\" value=\"Submit\" id='mypuka'/></div>"; } ?>
    <?php ActiveForm::end(); ?>
<?php
$this->registerJs("

    $('#mypuka').click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
       // saveSurvey($(this));
       // alert('Survey ready to save');
        var url=$('#survey-form').attr('action');
	$.post(url, $('#survey-form').serialize(),function (data){
        $('#customersurvey-modal').find('#customersurvey-modal-content').html(data);	});
        //alert('a4');
        return false;
    });

");
?>
