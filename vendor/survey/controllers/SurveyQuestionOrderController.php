<?php

namespace frontend\modules\survey\controllers;

use Yii;
use frontend\modules\survey\models\CrmSurveyQuestionOrder;
use frontend\modules\survey\models\CrmSurveyQuestionOrderSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SurveyQuestionOrderController implements the CRUD actions for CrmSurveyQuestionOrder model.
 */
class SurveyQuestionOrderController extends Controller
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
     * Lists all CrmSurveyQuestionOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
       /* $dataProvider = new ActiveDataProvider([
            'query' => CrmSurveyQuestionOrder::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);*/
        $searchModel = new CrmSurveyQuestionOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       //$dataProvider = $searchModel->campaginsearch();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
        
        
    }

    /**
     * Displays a single CrmSurveyQuestionOrder model.
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
     * Creates a new CrmSurveyQuestionOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CrmSurveyQuestionOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
                 $dataProvider = new ActiveDataProvider([
                 'query' => CrmSurveyQuestionOrder::find(),
				 			'pagination' => false,

                 ]);

                return $this->render('index', [
                    'dataProvider' => $dataProvider,
                ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CrmSurveyQuestionOrder model.
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
     * Deletes an existing CrmSurveyQuestionOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CrmSurveyQuestionOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmSurveyQuestionOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CrmSurveyQuestionOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
