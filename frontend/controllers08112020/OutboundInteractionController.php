<?php

namespace frontend\controllers;

use Yii;
use frontend\models\outboundInteraction\OutboundInteraction;
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

/**
 * InteractionController implements the CRUD actions for Interaction model.
 */
class OutboundInteractionController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'loadinteractions', 'populatereason', 'create', 'populateoutcome', 'populateescalateto', 'populateescalationlevel', 'createsurvey'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'loadinteractions', 'populatereason', 'populateoutcome', 'populateescalateto', 'populateescalationlevel', 'createsurvey'],
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
                            if (!Yii::$app->user->can('Create Interaction'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);

                            return Yii::$app->request->isAjax;
                        }
                    ],
                    [
                        'actions' => ['createsurvey'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->can('Create Interaction'))
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
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => OutboundInteraction::find()->where(['case_tbl_id' => -1])
                    //  ->joinWith('channel')
                    ->joinWith('createdBy'),
            'sort' => [
                'defaultOrder' => ['created_datetime' => SORT_DESC],
                'attributes' => [
                    'outbound_interaction_id',
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
    public function actionView($id) {
        return $this->renderAjax('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Interaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate0($case_id) {

        $model = new OutboundInteraction();
        $time = new \DateTime('now', new \DateTimeZone('Asia/Kuala_Lumpur'));
        $mystr = $time->format('Y-m-d h:i:s');
        $model->created_datetime = $mystr;
        $model->created_by = Yii::$app->user->identity->id;
        $createdate = $model->created_datetime;
        $createdatefinal = substr($createdate, 0, 10);
        $createdatefinal = substr($createdatefinal, 2, 10);
        $finaldate = str_replace("-", "", $createdatefinal);
        // var_dump( $createdatefinal);
        $query = "SELECT max(outbound_interaction_counter) as maxcount FROM outbound_interaction WHERE Date(created_datetime) = '$createdatefinal' ";
        $command = Yii::$app->db->createCommand($query);
        $result = $command->queryAll();
        $maxcount = $result[0]['maxcount'];
        $maxcountpad;
        $maxcountpadnum = intval($maxcount) + 1;
        if (strlen($maxcountpadnum) == 1) {
            $maxcountpadnum = "00" . $maxcountpadnum;
        } else if (strlen($maxcountpadnum) == 2) {
            $maxcountpadnum = "0" . $maxcountpadnum;
        }
        $interaction_id = "OUT" . $finaldate . $maxcountpadnum;
        $model->outbound_interaction_id = $interaction_id;
        $model->outbound_interaction_counter = intval($maxcount) + 1;
        $model->case_tbl_id = $case_id;
        // if(!$survey_response_id=='' && !$survey_response_id==null){     
        $outboudcount = OutboundInteraction::find()
                ->where(['case_tbl_id' => $case_id])
                ->count();
        //$outboudcount=(int)$outboudcount;        
        //var_dump($outboudcount);
        //  $model->created_by = Yii::$app->user->identity->id;
        //  $model->survey_response_id = $survey_response_id;
        //  $model->campaign_id = $survey_response_id;
        //  $model->customer_id = $customer_id;
        // }  else{
        //    return $this->redirect(Yii::$app->request->referrer);
        // }  
// $customermodel= Customer::find()->where(['id'=>$customer_id])->one();




        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            //  echo 'from here....';
            //  var_dump($model->save());
            //  print_r($model->getErrors());
            //die('DDDDD');
            // $model->customer_id = $customer_id;
            //SELECT COUNT(*) FROM outbound_interaction WHERE campaign_id=2 AND customer_id=25;
            // var_dump($model->outbound_interaction_counter);
            // die();
            // if($model->outbound_interaction_counter>=3){
            //     $model->save(false);
            //   if($model->case_status_id<10 && $model->case_status_id>0 ){
            //         // code for removing the current customer from the list of myinbox of agent and also removing the campaign outbound interactions;
            //               $model->save(false);
            //               $customermodel->contactable=0; 
            //               $customermodel->save(false);
            //           $usertoremove = ContactDistribution::find()
            //           ->where(['customer_id'=>$customer_id])
            //           ->one()
            //           ->delete();
            //               if($usertoremove){
            //                   return $this->redirect(Yii::$app->request->referrer);
            //               }
            //   }



            if ($model->save()) {


                return $this->redirect(Yii::$app->request->referrer);
                $body = $this->renderAjax('view', [
                    'model' => $model,
                    'customermodel' => $customermodel,
                ]);

                $hasError = false;
            } else {
                //die('puka1');
                $body = $this->renderAjax('create', ['model' => $model]);
                $hasError = true;
            }

            return ['body' => $body,
                'hasError' => $hasError,
                'id' => $model->id];
        } else {
            // die('puka2');
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionCreate($case_id) {


        $model = new OutboundInteraction();
        $model->case_tbl_id = $case_id;


//        if (($survey_response_id=='')||($survey_response_id==null))
//             $survey_response_id=0;
        $model->created_by = Yii::$app->user->identity->id;
        //$model->survey_response_id = $survey_response_id;
        //$model->campaign_id = $survey_response_id;
        //$model->customer_id = $customer_id;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            // echo 'from here....';
            // var_dump($model->save());
            // print_r($model->getErrors());
            // die('DDDDD');
            // $model->customer_id = $customer_id;

 // if($model->outbound_interaction_counter>=3){
            //     $model->save(false);
            //   if($model->interaction_status ){
            //         // code for removing the current customer from the list of myinbox of agent and also removing the campaign outbound interactions;
            //               $model->save(false);
            //               $customermodel->contactable=0; 
            //               $customermodel->save(false);
            //           $usertoremove = ContactDistribution::find()
            //           ->where(['customer_id'=>$customer_id])
            //           ->one()
            //           ->delete();
            //               if($usertoremove){
            //                   return $this->redirect(Yii::$app->request->referrer);
            //               }
            //   }

            if ($model->save()) {
                //echo 'DDDDDDD';
                        //die('ia, loast');
                $body = $this->renderAjax('view', ['model' => $this->findModel($model->getPrimaryKey()),]);
                $hasError = false;
            } else {

                $body = $this->renderAjax('create', ['model' => $model]);
                $hasError = true;
            }

            return ['body' => $body,
                'hasError' => $hasError,
                'id' => $model->id];
        } else {
			//die('outjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj');
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLoadinteractions($case_id) {
        $customer_id = 0; //$_GET['customer_id'];
        $survey_response_id = 0;
        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 3),
            'query' => OutboundInteraction::find()->where(['case_tbl_id' => $case_id])
                    // ->joinWith('channel')
                    ->joinWith('createdBy'),
            'sort' => [
                'defaultOrder' => ['created_datetime' => SORT_DESC],
                'attributes' => [
                    'outbound_interaction_id',
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
                    'survey_response_id' => $survey_response_id,
                    'customer_id' => $customer_id,
                    'case_id' => $case_id,
        ]);
    }

    /**
     * Finds the Interaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Interaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = OutboundInteraction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
