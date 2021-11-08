<?php

namespace frontend\controllers;

use Yii;
use frontend\models\customerEvents\CustomerEvents;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\db\Query;
use yii\helpers\Json;
use yii\filters\AccessControl;
use frontend\models\Events;
use frontend\models\customer\Customer;
use frontend\models\jmccrypt\JmcCrypt;


/**
 * ProductController implements the CRUD actions for Product model.
 */
class CustomerEventsController extends Controller
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

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
     
       $customer_id = $_POST['customer_id'];

        $dataProvider = new ActiveDataProvider([
            'query' => CustomerEvents::find()->where(['customer_id' => $customer_id]),
        ]);

        $customer_model = Customer::findOne($customer_id);

        return $this->render('index', [
            'CustomerEvents' => $dataProvider,
            'customer_id'=>$customer_id,
            'model'=>$customer_model,
        ]);
    }

    public function actionLoadevents($customer_id)
    {

        $dataProvider = new ActiveDataProvider([
            'query' => CustomerEvents::find()->where(['customer_id' => $customer_id]),
        ]);
        $customer_model = Customer::findOne($customer_id);
        return $this->renderAjax('index', [
            'CustomerEvents' => $dataProvider,
            'customer_id'=>$customer_id,
            'model'=>$customer_model,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      $val = $this->findModel($id);
    //  $result = Events::find()->where(['id' => $val->event_id]);
    $eventID = $val->event_id;
    //var_dump($this->findModel());
    //die();




      $val = $this->findModel($id);
    //  $result = Events::find()->where(['id' => $val->event_id]);
    $eventID = $val->event_id;
    //var_dump($this->findModel());
    //die();


        return $this->renderAjax('view', [
            'model' => $val,
            'result' => Events::findOne($eventID),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($customer_id)
    {
        $model = new CustomerEvents();
        $model->customer_id = $customer_id;
        $model->created_by = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post())&& Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if($model->save()) {
				          $val = $this->findModel($model->getPrimaryKey());
				              $eventID = $val->event_id;

                      //below code is to update the latest event for a customer...
                      $customer_model = Customer::findOne($customer_id);
                      $customer_model->latest_event = $eventID;
                      $customer_model->save(false);
                      //above codes end here....

                       $dataProvider = new ActiveDataProvider([
                           'query' => CustomerEvents::find()->where(['customer_id' => $customer_id]),
                       ]);

                         //$body = $this->renderAjax('index', ['CustomerEvents' => $dataProvider, 'customer_id'=>$customer_id,'model'=>$customer_model,]);
				                  $body = $this->renderAjax('view', ['model' => $val, 'result' => Events::findOne($eventID),]);
				                  $hasError = false;

           }else {

                $body = $this->renderAjax('create', ['model' => $model]);
                $hasError = true;
              }

              return ['body' => $body,
                         'hasError' => $hasError,
                         'id' => $model->id,
     					'customer_id' => $customer_id,];

        }
     		else {
     			       return $this->renderAjax('create', [
                     'model' => $model,
                     'customer_id' => $customer_id,
                 ]);
             }

    }
	public function actionDelete($id)
    {

		$model = CustomerEvents::findOne($id);
		$customer_id = $model->customer_id;
		$model->delete();
    $jmcIns = new JmcCrypt();
    $hashID= $jmcIns->HashMe($customer_id);

		return $this->redirect(['//customer/'.$customer_id.'?jmc='.$hashID]);
	}
    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->save()) {
                $body = $this->renderAjax('view', ['model' => $this->findModel($model->getPrimaryKey()),]);
                $hasError = false;
            }
            else {
                $body = $this->renderAjax('update', ['model' => $model]);
                $hasError = true;
            }
            return ['body' => $body,
                    'hasError' => $hasError,
                    'id' => $model->id,];

        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }
*/
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerEvents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerEvents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
