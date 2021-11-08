<?php

namespace frontend\controllers;

    use Yii;
    use frontend\modules\survey\models\CrmSurveyResponse;
    use frontend\models\outboundInteraction\OutboundInteraction;
	use frontend\models\cases\CustomerCases;

    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\db\Query;
    use yii\data\SqlDataProvider;
    use yii\web\Response;
    use frontend\models\jmccrypt\JmcCrypt;
    

/**
 * CustomerSurveyController implements the CRUD actions for CrmSurveyResponse model.
 */
class CustomerSurveyController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all CrmSurveyResponse models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CrmSurveyResponse::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CrmSurveyResponse model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CrmSurveyResponse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

 public function actionCreate($respondent_id)
    {
        $model = new CrmSurveyResponse();
        $caseid=$_GET['case_id']; 
        $model->respondent_id = $respondent_id;
		
        if ($model->load(Yii::$app->request->post())){
			

          	$model->case_id=$caseid;
            $this_survey_id=$_POST['CrmSurveyResponse']['survey_id'];
              
               if($this_survey_id==75){
				  $case=  CustomerCases::findOne(['id' => $caseid]);
				 // $case->inbound_csat=1;
				  $case->save();
				} 
			   if($this_survey_id==89){
				$case=  CustomerCases::findOne(['id' => $caseid]);
				$case->outbound_csat=1;
									

				$case->save();
				 
          

         }

         if( $model->save() ){
          	 Yii::$app->session->setFlash('success', "Survey successfully added.");
            return $this->redirect(Yii::$app->request->referrer);
		} else if(!$model->save()){
			$errores = $model->getErrors();
			var_dump($errores);
           Yii::$app->session->setFlash('error', "Survey already added.");
			return $this->redirect(Yii::$app->request->referrer);                   


         }

		return $this->redirect(['//customer/'.$respondent_id.'?outbnd=100&jmc='.$hashID]);
        } else {
              
            $body = $this->renderAjax('create', ['case_id'=>0 ,'model' => $model]);
             $hasError = true;

            return $this->renderAjax('create', [
               
                'body' => $body,
                'model' => $model,
                'hasError' => $hasError,
                'respondent_id'=>$respondent_id,
            ]);
        }


    }



    /**
     * Updates an existing CrmSurveyResponse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CrmSurveyResponse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

     public function actionStart()
    {
       // die('ssssssssssssssssssss');
         $crm_survey_response_id=$_GET['crm_survey_response_id'];
         $customer_id=$_GET['customer_id'];
         $case_id= $_GET["case_id"];

		$model = new CrmSurveyResponse();
		$model->respondent_id = $customer_id;
		$model->case_id = $case_id;
		$model->survey_id = $crm_survey_response_id;
		$model->campaign_id = $crm_survey_response_id;
        $model->save();
		
		$case=  CustomerCases::findOne(['id' => $case_id]);
		$case->outbound_csat=1;
		$case->created_by=Yii::$app->user->identity->id;
		//$case->save();

         //GET ALL QUESTIONS AND ORDER

         //GET ANSWER CHOICE
         return $this->renderAjax('start',['crm_survey_response_id'=>$crm_survey_response_id,'customer_id'=>$customer_id,'case_id'=>$case_id]);
    }

     public function actionSavesurvey(){
         // echo "save survey called ";
         // die(); 

          Yii::$app->response->format = Response::FORMAT_JSON;
        
         $survey_response_id=$_GET['crm_survey_response_id'];
         $survey_id=$_POST['survey_id']; //$survey_response_id;
         $case_id=$_GET['case_id'];





         if(isset($_GET["case_id"]))
          {$case_id= $_GET["case_id"];
            // var_dump($case_id);
            // die();

       } else{$case_id= 0;};
         
         $customer_id=$_GET['customer_id'];
         $checkBoxOption=[];
         $radioOption=[];
         //clear old surveys
           $deleteQry="DELETE FROM crm_survey_result WHERE survey_response_id=$survey_response_id And case_id=$case_id AND respondent_id=$customer_id";
         Yii::$app->db->createCommand($deleteQry)->execute();
         //Update the survey completion

         $updateQry="UPDATE crm_survey_response SET completed_at=NOW() WHERE survey_id=$survey_response_id And case_id=$case_id AND respondent_id=$customer_id";
         //Yii::$app->db->createCommand($updateQry)->execute();
           Yii::$app->db->createCommand($updateQry)->execute();
     
     
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
                            WHERE
                              `crm_survey_question_order`.`servey_id`=$survey_id ORDER BY `crm_survey_question_order`.`order` ")->queryAll();

    foreach ($surveyQuiz as $key => $value) {
          $qId= $value['question_id'];
          $qType=$value['question_type_id'];
          
           if ($qType==1){
              $radioOption=$_POST['radioOption_'.$qId];
              $questionId=(int)$qId;
              $ansId =(int)$radioOption;
              
               if (isset($_POST['radioOptionOtherComment_'.$qId]))
                         $radioOptionOtherComment=$_POST['radioOptionOtherComment_'.$qId];
                     
              
              else 
                 $radioOptionOtherComment="";
              $insertResponse="insert into `crm_survey_result` (`survey_response_id`,`respondent_id`,  `question_id`, `answer`,`other_comment`,`case_id` )
                           values ('$survey_response_id', '$customer_id', '$questionId', '$ansId','$radioOptionOtherComment','$case_id')";
              $result=Yii::$app->db->createCommand($insertResponse)->execute();

           }
           else if ($qType==2){
             $checkBoxOption=$_POST['checkBoxOption_'.$qId];
              
                foreach ($checkBoxOption as $value) {
                 $questionId=(int)substr($value,0,strrpos($value,'_'));
                 $ansId=(int)substr($value,2,strrpos($value,'_'));
                 $ansId =(int)Yii::$app->db->quoteValue($ansId);
               
                 $insertResponse="insert into `crm_survey_result` (`survey_response_id`,`respondent_id`,  `question_id`, `answer`,`case_id`)
                         values ('$survey_response_id', '$customer_id', '$questionId', '$ansId','$case_id' )";
                    $result=Yii::$app->db->createCommand($insertResponse)->execute();
               }

           }
           else {
             $textTypeQs=$_POST['textOption_'.$qId];
               $questionId=(int)$qId;
               $q6query= "insert into `crm_survey_response_choice` (`text`,`is_deleted`) value ('$textTypeQs','0')";
              $qresult= Yii::$app->db->createCommand($q6query)->EXECUTE();
              $last_id= Yii::$app->db->getLastInsertID();
              $last_id= (int)$last_id;
             $ansId =$last_id;
             $insertResponse="insert into `crm_survey_result` (`survey_response_id`,`respondent_id`,  `question_id`,`case_id` ,`answer`)
                           values ('$survey_response_id', '$customer_id', '$questionId','$case_id', '$ansId' )";
              $result= Yii::$app->db->createCommand($insertResponse)->execute();
          
         }
    }
     
        
     
            Yii::$app->response->format = Response::FORMAT_JSON;
       if ($result==1){
          
          if($survey_response_id==75){
            $model = new InboundInteraction();
            $model->case_id =$case_id; 
            $time = new \DateTime('now', new \DateTimeZone('Asia/Kuala_Lumpur'));
            $mystr = $time->format('Y-m-d h:i:s');
            $model->created_datetime=$mystr;

            return  $this->renderAjax('//inbound-interaction/create', ['model' => $model,'']);

          }

           $model = new OutboundInteraction();
		    $model->case_tbl_id =$case_id; 

           $model->campaign_id =$survey_response_id;// $campaign_id;
         // return $this->redirect(['//customer/'.$customer_id.'?outbnd=100']);
//die('PUKA');
          $model->survey_response_id=$survey_response_id;
          $model->customer_id=$customer_id;
          return  $this->renderAjax('//outbound-interaction/create', ['model' => $model,'']);

       }
    else{

      $jmcIns = new JmcCrypt();
      $hashID= $jmcIns->HashMe($customer_id);

      return $this->redirect(['//customer/'.$customer_id.'?outbnd=100&jmc='.$hashID]);
       //return $this->redirect(['//customer/'.$customer_id.'?outbnd=100']);
      }
    }
    
    
    public function actionLoadinteractions($case_id)
    {
        $customer_id=$_GET['customer_id'];
        $dataProvider = new ActiveDataProvider([
                                               'pagination' => array('pageSize' => 3),
                                               'query' => OutboundInteraction::find()->where(['case_tbl_id' => $case_id])
                                               // ->joinWith('channel')
                                               ->joinWith('creator'),
                                               'sort' => [
                                               'defaultOrder' => ['created_datetime'=>SORT_DESC],
                                               'attributes' => [
                                               'outbound_interaction_id',
                                               'creator.username' => [
                                               'asc' => ['user.username' => SORT_ASC],
                                               'desc' => ['user.username' => SORT_DESC],
                                               'default' => SORT_DESC
                                               ],
                                               'created_datetime'
                                               ]
                                               ],
                                               ]);
        //die('$dataProvider');
        
        return $this->renderAjax('index', [
                                 'interactions' => $dataProvider,
                                 'survey_response_id' => $survey_response_id,
                                 'customer_id' =>$customer_id,
                                 ]);
    }

    /**
     * Finds the CrmSurveyResponse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmSurveyResponse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CrmSurveyResponse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
