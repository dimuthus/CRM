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

use frontend\models\request\Request;
use frontend\models\request\RequestSearch;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'search', 'view', 'create', 'update', 'aging', 'populatecenters',
                        'populatedetails','populateadditionals','populatesupplementals'],
                'rules' => [
                    [
                       'actions' => ['index'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Service Request Page'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }

                    ],
                    [
                       'actions' => ['aging'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('SR Aging Page'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }

                    ],
                    [
                       'actions' => ['create'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Create Service Request'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);
                            return Yii::$app->request->isAjax;
                        }

                    ],
                    [
                       'actions' => ['update'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Update Service Request'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);
                            return Yii::$app->request->isAjax;
                        }

                    ],
                    [
                       'actions' => ['search', 'view', 'populatecenters',
                                    'populatedetails','populateadditionals','populatesupplementals'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Yii::$app->request->isAjax;
                       }
                    ]
               ],
            ]
        ];
    }

    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new RequestSearch();
        $query = Request::find()
            ->where('user.id != 1 AND service_request.service_request_status not in (1,3,4,12,18)')
            ->joinWith('customer')
            ->joinWith('type')
            ->joinWith('status')
            ->joinWith([
                'escalated' => function ($q) {
                    $q->from('user es');
                },
            ])
            ->joinWith('prioritytitle')
            ->joinWith('creator');

        if(!Yii::$app->user->can('View Service Reuqests Created by All Agents'))
            $query->andFilterWhere(['service_request.created_by'=> Yii::$app->user->identity->id]);

        $dataProvider = new ActiveDataProvider([
            'pagination' => ['pageSize'=>10],
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['creation_datetime'=>SORT_DESC],
                'attributes' => [
                    'customer.full_name' => [
                        'asc' => ['customer.full_name' => SORT_ASC],
                        'desc' => ['customer.full_name' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'service_request_id',
                    'type.name' => [
                        'asc' => ['service_request_type.name' => SORT_ASC],
                        'desc' => ['service_request_type.name' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'status.name' => [
                        'asc' => ['service_request_status.name' => SORT_ASC],
                        'desc' => ['service_request_status.name' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'escalated.username' => [
                        'asc' => ['es.username' => SORT_ASC],
                        'desc' => ['es.username' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'prioritytitle.name' => [
                        'asc' => ['service_type_priority.name' => SORT_ASC],
                        'desc' => ['service_type_priority.name' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'creator.username' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'creation_datetime' => [
                        'asc' => ['service_request.creation_datetime' => SORT_ASC],
                        'desc' => ['service_request.creation_datetime' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                ]
            ]
        ]);
    
        return $this->render('requests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSearch()
    {
        $params =Yii::$app->request->queryParams;

        $searchModel = new RequestSearch();
        $results = $searchModel->search($params);
        // print_r($results);
       // die();
        return $this->renderAjax('request_list', [
            'dataProvider' => $results,
        ]);

    }

    public function actionAging()
    {
        $query = new Query;

        $query->select('*')->from('vi_service_request_aging');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('aging', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->productIds = $model->getProductIds();

        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($customer_id)
    {
        $model = new Request();

        $model->customer_id = $customer_id;
        $model->created_by = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->save()) {
                $body = $this->renderAjax('view', ['model' => $this->findModel($model->getPrimaryKey()),]);
                $hasError = false;
            }
            else {
                $body = $this->renderAjax('create', ['model' => $model]);
                $hasError = true; 
            }
            return ['body' => $body, 
                    'hasError' => $hasError, 
                    'id' => $model->id,];
            
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->productIds = $model->getProductIds();

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->save()) {
                $model = $this->findModel($model->getPrimaryKey());
                $model->productIds = $model->getProductIds();
                $body = $this->renderAjax('view', ['model' => $model,]);
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


    /**
     * Genereate the dropdown list for request detail type based on request type selected.
     * @return mixed
     */
    public function actionPopulatedetails()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $type_id = $parents[0];
            if ($type_id != null) {
                $out = self::getRequestDetails($type_id); 
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

    /**
     * Genereate the dropdown list for additional detail type based on detail request type selected.
     * @return mixed
     */
    public function actionPopulateadditionals()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $detail_id = $parents[0];
            if ($detail_id != null) {
                $out = self::getRequestAdditioanls($detail_id); 
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

    /**
     * Genereate the dropdown list for supplemental detail type based on additional request type selected.
     * @return mixed
     */
    public function actionPopulatesupplementals()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $additional_id = $parents[0];
            if ($additional_id != null) {
                $out = self::getRequestSupplementals($additional_id); 
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }

    /**
     * Genereate the dropdown list for request service center based on country selected.
     * @return mixed
     */
    public function actionPopulatecenters()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $country_code = $parents[0];
            if ($country_code != null) {
                $out = self::getServiceCenters($country_code); 
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }



    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Helper dropdown population functions
     */

    
    /**
     * queries values for request detail type dropdown based on request type selected.
     * @return mixed
     */
    protected function getRequestDetails ($request_type) {
        $query = new Query;
        $query->select('id, name')
            ->from('service_request_detail_type')
            ->where('deleted = 0 and type_id = '.$request_type)
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }

    /**
     * queries values for request additional type dropdown based on detail request type selected.
     * @return mixed
     */
    protected function getRequestAdditioanls ($detail_id) {
        $query = new Query;
        // compose the query
        $query->select('id, name')
            ->from('service_request_additional_detail')
            ->where('deleted = 0 and detail_type_id = '.$detail_id)
            ->orderBy('name');
        // build and execute the query
        $rows = $query->all();

        return $rows;
    }

    /**
     * queries values for request supplemental type dropdown based on additional request type selected.
     * @return mixed
     */
    protected function getRequestSupplementals ($additional_id) {
        $query = new Query;
        // compose the query
        $query->select('id, name')
            ->from('service_request_supplemental_detail')
            ->where('deleted = 0 and additional_type_id = '.$additional_id)
            ->orderBy('name');
        // build and execute the query
        $rows = $query->all();

        return $rows;
    }

    /**
     * queries values for request service centers dropdown based on country selected.
     * @return mixed
     */
    protected function getServiceCenters ($country_code) {
        $query = new Query;
        // compose the query
        $query->select('id, name')
            ->from('service_request_service_center')
            ->where("deleted = 0 and country_id = '".$country_code."'")
            ->orderBy('name');
        // build and execute the query
        $rows = $query->all();

        return $rows;
    }

    
}
