<?php

namespace frontend\controllers;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\db\Query;
use yii\helpers\Json;
use yii\filters\AccessControl;
use frontend\models\Logadm;
use frontend\models\Events;
use frontend\models\campaign\CustomerCampaign;
use frontend\models\jmccrypt\JmcCrypt;
use yii\bootstrap\ActiveForm;

//use frontend\models\campaign\CustomerCampaign;

class CacController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update'],
                'rules' => [
                    [
                       'actions' => ['index', 'view'],
                       'allow' => true,
                       'roles' => ['@'],

                    ],
                    [
                       'actions' => ['create'],
                       'allow' => true,
                       'roles' => ['@'],

                    ],
                    [
                       'actions' => ['update'],
                       'allow' => true,
                       'roles' => ['@'],

                    ]
                ],
            ],

        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
   
    public function actionCreate($customer_id)
    {
        
        $model = new CustomerCampaign();
	$model->customer_id=$customer_id;	
	//	$model->distributed_by = Yii::$app->user->identity->id;
		  


        if ($model->load(Yii::$app->request->post())  ) {

            $data=Yii::$app->request->post();
            $jmcIns = new JmcCrypt();
            $hashID= $jmcIns->HashMe($customer_id);
            if($model->save(true)){
               $logmodel= new Logadm();
            $logmodel->createInsertLog($data,$model);
              return $this->redirect(['//customer/'.$customer_id.'?outbnd=100&jmc='.$hashID]);

            } else {

                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ActiveForm::validate($model);


            }

            
            //return $this->redirect(['view', 'id' => $model->id]);
        }
        else{
            
             $body = $this->renderAjax('create', ['model' => $model]);
             $hasError = true;
               return $this->renderAjax('create', [
                   'body' => $body,
                  'model' => $model,
                   'hasError' => $hasError,
                   'customer_id'=>$customer_id
               ]);
        }
      
    }
}
