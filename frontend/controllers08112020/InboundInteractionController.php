<?php

namespace frontend\controllers;

use Yii;
use frontend\models\interaction\InboundInteraction;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\helpers\Json;
use yii\filters\AccessControl;
use frontend\models\cases\CustomerCases;
use frontend\models\cases\CaseStatus;
use yii\data\ArrayDataProvider;
use frontend\models\jmccrypt\JmcCrypt;
use frontend\models\Logadm;
/**
 * InteractionController implements the CRUD actions for Interaction model.
 */
class InboundInteractionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'loadinteractions','populatereason', 'create', 'populateoutcome','populateescalateto', 'populateescalationlevel','createsurvey'],
                'rules' => [
                    [
                       'actions' => ['index', 'view', 'loadinteractions','populatereason', 'populateoutcome','populateescalateto', 'populateescalationlevel','createsurvey' ],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Yii::$app->request->isAjax;
                       }
                    ],
                    [
                       'actions' => ['create'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           if(!Yii::$app->user->can('Create Interaction'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);

                            return Yii::$app->request->isAjax;
                       }
                    ],
                    [
                    'actions' => ['createsurvey'],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {
                        if(!Yii::$app->user->can('Create Interaction'))
                            throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);

                        return Yii::$app->request->isAjax;
                    }
                    ]
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Interaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => InboundInteraction::find()->where(['case_tbl_id' => -1])
          //  ->joinWith('channel')
            ->joinWith('createdBy'),
            'sort' => [
                'defaultOrder' => ['created_datetime'=>SORT_DESC],
                'attributes' => [
                    'interaction_id',
                 /*   'channel.name' => [
                        'asc' => ['outcome_code1.name' => SORT_ASC],
                        'desc' => ['outcome_code1.name' => SORT_DESC],
                        'default' => SORT_DESC
                    ],*/
                    'createdBy.username' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'created_datetime'
                ]
            ],
        ]);

        return $this->renderAjax('index', [
            'interactions' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Interaction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a list of Interactions based on request id.
     * @param integer $id
     * @return mixed
     */
    public function actionLoadinteractions($case_id)
    {
		$caseStatus=$this->getCasestaus($case_id);
	    //die('caseStatus'.$caseStatus);
        $dataProvider = new ActiveDataProvider([
            'pagination' =>false, // array('pageSize' => 3),
            'query' => InboundInteraction::find()->where(['case_tbl_id' => $case_id])
           // ->joinWith('channel')
            ->joinWith('createdBy'),
            'sort' => [
                'defaultOrder' => ['created_datetime'=>SORT_DESC],
                'attributes' => [
                    'interaction_id',
                    /*'channel.name' => [
                        'asc' => ['outcome_code1.name' => SORT_ASC],
                        'desc' => ['outcome_code1.name' => SORT_DESC],
                        'default' => SORT_DESC
                    ],*/
                    'createdBy.username' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'created_datetime'
                ]
            ],
        ]);
	//die('caseStatus'.$caseStatus);
        return $this->renderAjax('index', [
            'interactions' => $dataProvider,
            'case_id' => $case_id,
			'caseStatus'=>$caseStatus,
        ]);
    }


    /**
     * Creates a new Interaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($case_id)
    {

        $model = new InboundInteraction();
        $model->created_by = Yii::$app->user->identity->id;
        $model->case_tbl_id = $case_id;

        $case_model = CustomerCases::findOne($model->case_tbl_id);
        $time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
        $mystr = $time->format('Y-m-d h:i:s');
        $model->created_datetime=$mystr;
	//	$cs=$case_model['case_status'];
		//$model->case_status=$case_model['case_status'];
       // $model->case_status=$case_status;
		/* && Yii::$app->request->isAjax */
		//$model->customer_id = $customer_id;

     //   $cus_id=$model->case_id;
        if ($model->load(Yii::$app->request->post())&& Yii::$app->request->isAjax) {
             $data=Yii::$app->request->post();



            Yii::$app->response->format = Response::FORMAT_JSON;

			/*
			$model->myfile = UploadedFile::getInstances($model, 'myfile');
			//	die($model->myfile);


		   foreach ($model->myfile as $file) {
                $time = new \DateTime('now');
                //$mystr = $time->format('Ymdhis');
                $mystr =round(microtime(true)).rand(1000,9999);
                $ori_filename=$file->name;
                $model->attachment_name= $model->attachment_name."#LRL#".$ori_filename;
				//die($model->attachment_name);
                $c_filename=$mystr."-".$cus_id.".".$file->extension;
                $file->saveAs( 'uploads/'. $c_filename);
                $model->attachment_url=$model->attachment_url."#LRL#".'uploads/'.$c_filename;
                //echo "<br/>".$model->attachment_url;
            }
			*/
			/*
            $model->myfile=  UploadedFile::getInstance($model, 'myfile');

            if ($model->myfile != NULL){
				  die($model->myfile);
             $time = new \DateTime('now');
             $mystr = $time->format('Ymdhis');
             $ori_filename=$model->myfile->name;
             $model->attachment_name=   $ori_filename;
             $c_filename=$mystr."-".$cus_id.".".$model->myfile->extension;
             $model->myfile->saveAs( 'uploads/'. $c_filename);
             $model->attachment_url='uploads/'.$c_filename;
			}
             */
            //Added By Ahsan (CR 2015/55)
             //echo $model->case_id;
             // die(23234);
         //   $case_model = new Cases();
          //  $case_model = Cases::findOne($model->case_id);
          //  $case_model->case_status =$model->case_status;
           /* if ($model->case_status ==2 || $model->case_status ==4){
                $case_model->escalated_to=NULL;
                $case_model->followup_datetime=NULL;
            }*/

           // $case_model->save(false);
            //print_r($model);
            //die(21341);
           //var_dump($model);
		//	die('am here....');
            if($model->save()) {
				//echo "model saved";
				// if($model->interaction_status==4){
                // $body = $this->renderAjax('create_survey', ['model' => $this->findModel($model->getPrimaryKey())]);

               // }
               // else{ 
			         // $body = $this->renderAjax('view', ['model' => $this->findModel($model->getPrimaryKey()),]);
               // }
			   
             $logmodel= new Logadm();
            $logmodel->createInsertLog($data,$model);
			   	$body = $this->renderAjax('view', ['model' => $this->findModel($model->getPrimaryKey()),]);
			    $hasError = false;
            }
            else {

		 $body = $this->renderAjax('create', ['model' => $model]);
                $hasError = true;
            }
			// print_r($body);

			return ['body' => $body,
                    'hasError' => $hasError,
                    'id' => $model->id,
					'case_id' => $model->case_tbl_id];
               //die(23542345);
        }
		else {
                        
                    //$errores = $model->getErrors();
			//var_dump($errores);
		    // die('XXXXXXXXXXXXX');
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }


  public function actionPopulatereason()
    {
        $out = [];//print_r($_POST['depdrop_parents']);
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $status_id = $parents[0];
            if ($status_id != null) {
                $out = self::getReason($status_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

	public function actionPopulateoutcome()
    {
        $out = [];//print_r($_POST['depdrop_parents']);
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $channel_id = $parents[0];
            if ($channel_id != null) {
                $out = self::getOutcome($channel_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

	public function actionPopulateescalationlevel()
    {
        $out = [];//print_r($_POST['depdrop_parents']);
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $current_outcome = $parents[0];
            if ($current_outcome != null) {
                $out = self::getEscalationLevel($current_outcome);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

	public function actionPopulateescalateto()
    {
        $out = [];//print_r($_POST['depdrop_parents']);
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
           // $current_outcome = $parents[0];
			$escalation_level = $parents[0];
            if ($escalation_level != null) {
                $out = self::getEscalateTo($escalation_level);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

	protected function getEscalateTo($escalation_level) {
        $query = new Query;
        $query->select('id, name')
            ->from('escalated_to')
            ->where('deleted = 0 and escalation_level_id = '.$escalation_level)
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
	/**
     * queries values for for call reason based on call status selected.
     * @return mixed
     */
    protected function getReason($status_id) {
        $query = new Query;
        $query->select('id, name')
            ->from('interaction_reason')
            ->where('deleted = 0 and interaction_status_id = '.$status_id)
            ->orderBy('name');
        $rows = $query->all();
		//print_r($rows);
        return $rows;
    }

	protected function getOutcome($channel_id) {
        $query = new Query;
        $query->select('id, name')
            ->from('interaction_current_outcome')
            ->where('deleted = 0 and channel_id = '.$channel_id)
            ->orderBy('name');
        $rows = $query->all();
		//print_r($rows);
        return $rows;
    }

	protected function getEscalationLevel($current_outcome){
		$query = new Query;
		$query->select('id, name')
			->from('escalation_level')
			->where('deleted=0 and interaction_outcome_id = '.$current_outcome)
			->orderBy('name');
		$rows = $query->all();
		return $rows;
	}
	
	
	protected function getCasestaus($case_id){
		$query = new Query;
		$query->select('case_status')
			->from('customer_cases')
			->where('id = '.$case_id);
		$rows = $query->all();
//return $rows['case_status'];
		foreach ($rows as $modelData) {
            
				return $modelData['case_status'];
        }
	}

    /**
     * Finds the Interaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Interaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InboundInteraction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
