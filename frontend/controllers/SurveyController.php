<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use frontend\models\SurveyQuiz;
use frontend\models\SurveySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\interaction\Interaction;
use frontend\models\cases\Cases;
use frontend\models\cases\CaseStatus;
use frontend\models\survey\SurveyResponse;
use frontend\models\Logadm();

/**
 * SurveyController implements the CRUD actions for SurveyQuiz model.
 */
class SurveyController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SurveyQuiz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SurveySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SurveyQuiz model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SurveyQuiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SurveyQuiz();
        die('puka');
        if ($model->load(Yii::$app->request->post())) {
            $data= Yii::$app->request->post();
           if($model->save()){

             $logmodel= new Logadm();
            $logmodel->createInsertLog($data,$model);
            
           }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
	
	public function actionCreatesurvey($case_id){
			$model = new SurveyResponse();
			$model->request_id =  $case_id;
			
			if ($model->load(Yii::$app->request->post()) ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
           //		die($id . " model id");
            $delPreviousSurvey="DELETE FROM survey_response WHERE request_id='$case_id'";
            \Yii::$app->db->createCommand($delPreviousSurvey)->execute();
            $radioQuiz_1=$_POST['radioQuiz_1'];
        /*    $txtboxQuiz_1_1=$_POST['txtboxQuiz_1_1'];
            if (isset($_POST['checkBox_2_2'])) $checkBox_2_2=1; else  $checkBox_2_2=0;  
            if (isset($_POST['checkBox_2_3'])) $checkBox_2_3=1; else  $checkBox_2_3=0;  
            if (isset($_POST['checkBox_2_4'])) $checkBox_2_4=1; else  $checkBox_2_4=0;  
            if (isset($_POST['checkBox_2_5'])) $checkBox_2_5=1; else  $checkBox_2_5=0;  
            if (isset($_POST['checkBox_2_6'])) $checkBox_2_6=1; else  $checkBox_2_6=0;  
            if (isset($_POST['checkBox_2_7'])) $checkBox_2_7=1; else  $checkBox_2_7=0;  
            if (isset($_POST['checkBox_2_8'])) $checkBox_2_8=1; else  $checkBox_2_8=0;  
            if (isset($_POST['checkBox_2_9'])) $checkBox_2_9=1; else  $checkBox_2_9=0;  
            if (isset($_POST['checkBox_2_10']))$checkBox_2_10=1;else  $checkBox_2_10=0;  
            if (isset($_POST['checkBox_3_11']))$checkBox_3_11=1;else  $checkBox_3_11=0;  
            if (isset($_POST['checkBox_3_12']))$checkBox_3_12=1;else  $checkBox_3_12=0;  
            if (isset($_POST['checkBox_3_13']))$checkBox_3_13=1;else  $checkBox_3_13=0;  
            if (isset($_POST['checkBox_3_14']))$checkBox_3_14=1;else  $checkBox_3_14=0;  */
            $radioQuiz_2_11=$_POST['radioQuiz_2'];
          //  $radioQuiz_2_12=$_POST['radioQuiz_2_12'];
            $radioQuiz_3_13=$_POST['radioQuiz_3'];
          //  $radioQuiz_3_14=$_POST['radioQuiz_3_14'];
            $radioQuiz_4_15=$_POST['radioQuiz_4'];
          //  $radioQuiz_4_16=$_POST['radioQuiz_4_16'];
          // $radioQuiz_5_17=$_POST['radioQuiz_5_17'];
            $radioQuiz_5_18=$_POST['radioQuiz_5'];
        /*    $radioQuiz_5_19=$_POST['radioQuiz_5_19'];
            $radioQuiz_5_20=$_POST['radioQuiz_5_20'];
            $radioQuiz_5_21=$_POST['radioQuiz_5_21'];*/
            $txtboxQuiz_6=$_POST['txtboxQuiz_6'];
			$sql="INSERT INTO `survey_response`
            ( `request_id`,`question_id`, `sub_question_id`, `response`)VALUES 
            ('$case_id',1,0,'$radioQuiz_1'),"
                    /*    . "('$request_id',1,1,'$txtboxQuiz_1_1'),"
                        . "('$request_id',2,2,'$checkBox_2_2'),"
                        . "('$request_id',2,3,'$checkBox_2_3'),"
                        . "('$request_id',2,4,'$checkBox_2_4'),"
                        . "('$request_id',2,5,'$checkBox_2_5'),"
                        . "('$request_id',2,6,'$checkBox_2_6'),"
                        . "('$request_id',2,7,'$checkBox_2_7'),"
                        . "('$request_id',2,8,'$checkBox_2_8'),"
                        . "('$request_id',2,9,'$checkBox_2_9'),"
                        . "('$request_id',2,9,'$checkBox_2_9'),"
                        . "('$request_id',3,11,'$checkBox_3_11'),"
			. "('$request_id',3,12,'$checkBox_3_12'),"
			. "('$request_id',3,13,'$checkBox_3_13')," 
			. "('$request_id',3,14,'$checkBox_3_14')," */
			. "('$case_id',2,0,'$radioQuiz_2_11'),"
		//	. "('$request_id',2,0,'$radioQuiz_2_12'),"
			. "('$case_id',3,0,'$radioQuiz_3_13'),"
		//	. "('$request_id',3,0,'$radioQuiz_3_14'),"
			. "('$case_id',4,0,'$radioQuiz_4_15'),"
		//	. "('$request_id',4,0,'$radioQuiz_4_16'),"
		//	. "('$request_id',5,0,'$radioQuiz_5_17'),"
			. "('$case_id',5,0,'$radioQuiz_5_18'),"
		/*	. "('$request_id',5,0,'$radioQuiz_5_19'),"
			. "('$request_id',5,0,'$radioQuiz_5_20'),"
			. "('$request_id',5,0,'$radioQuiz_5_21'),"
			. "('$request_id',8,25,'$radioQuiz_8_25'),"
			. "('$request_id',8,26,'$radioQuiz_8_26'),"
			. "('$request_id',8,27,'$radioQuiz_8_27'),"
			. "('$request_id',9,28,'$radioQuiz_9_28'),"
			. "('$request_id',9,29,'$radioQuiz_9_29')," */
			. "('$case_id',6,0,'$txtboxQuiz_6')";
		//	. "('$request_id',10,30,'$txtboxQuiz_10_30')";
            //echo $sql;
            $resuilt=\Yii::$app->db->createCommand($sql)->execute();
			
            if($resuilt && $model->save()){				
				//die("I am inside saved survey model");	
               $interactionModel = new Interaction();
               $interactionModel->created_by = Yii::$app->user->identity->id;
               $interactionModel->case_id = $case_id; 
				
				$case_model = Cases::findOne($case_id);
						 
				$interactionModel->case_status = $case_model['case_status']; 
			   // var_dump($interactionModel);
                $body = $this->renderAjax('//interaction/create', ['model' => $interactionModel]);
                $hasError = false;
				
              //  \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
              //  Yii::$app->response->format = Response::FORMAT_JSON;
				return ['body' => $body,
                    'hasError' => $hasError, 
                    'id' => $interactionModel->case_id];
            }
		}
		else {
		return $this->renderAjax('create_survey', [
			'model' => $model,
		]);
	}			
          //  die('actionCreatesurvey');
    }
	
	
    /**
     * Updates an existing SurveyQuiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SurveyQuiz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SurveyQuiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SurveyQuiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SurveyQuiz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
