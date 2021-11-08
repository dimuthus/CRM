<?php

namespace frontend\controllers;

use Yii;
use frontend\models\campaign\Campaign;
use frontend\models\campaign\CampaignSearch;
use frontend\models\Logadm;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\filters\AccessControl;
/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class CampaignController extends Controller
{
    /**
     * @inheritdoc
     */
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Campaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CampaignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Campaign model.
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
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Campaign();
        $query = new Query;
        if ((Yii::$app->request->post('hasCreateCampaign')) && (Yii::$app->request->isAjax)) {
        //Yii::$app->response->format = Response::FORMAT_JSON;
        $model->load(Yii::$app->request->post());
    	$model->created_by = Yii::$app->user->identity->id;
			$model->last_updated_by = Yii::$app->user->identity->id;
			$time = new \DateTime('now', new \DateTimeZone('Asia/Kuala_Lumpur'));
        $mystr = $time->format('Y-m-d h:i:s');
        $model->last_updated_datetime=$mystr;
        if($model->save()){
			   $logmodel= new Logadm();
            $logmodel->createInsertLog($data,$model);
              return $this->redirect(['index']);

       //unset($_POST['hasCreateCampaign']);
				//return $this->redirect(['view', 'id' => $model->id]);
        //return $this->redirect(['index']);
        //Yii::app()->end();
      }
			else{
				return $this->render('create', ['model' => $model,]);
      }
    } else {

      return $this->render('create', [
          'model' => $model,
      ]);

    }
        //echo 'Am outside';
        //var_dump($model);
        //die();


  }

    /**
     * Updates an existing Campaign model.
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
     * Deletes an existing Campaign model.
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
     * Finds the Campaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Campaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Campaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
