<?php

namespace frontend\modules\survey\controllers;

use Yii;
use frontend\modules\survey\models\CrmSurveyResponseChoice;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;



/**
 * SurveyResponseChoiceController implements the CRUD actions for CrmSurveyResponseChoice model.
 */
class SurveyResponseChoiceController extends Controller
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
     * Lists all CrmSurveyResponseChoice models.
     * @return mixed
     */
    
    
    public function actionSurveyResponseList(){
        
         if (Yii::$app->request->post('hasEditable')) {
            //echo "This action is here :hasEditable";
           // die(121234);
            $id = Yii::$app->request->post('editableKey');
            $model = CrmSurveyResponseChoice::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CrmSurveyResponseChoice']);
            $post['CrmSurveyResponseChoice'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }
        
              $model = new CrmSurveyResponseChoice();
        
          if (Yii::$app->request->get('refresh-widget')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $question_id = Yii::$app->request->get('question_id');
            
            $qTypeQry="SELECT question_type_id FROM crm_survey_question  WHERE `id`=$question_id  AND is_deleted=0";
$qTypeRes = Yii::$app->db->createCommand($qTypeQry)->queryOne();
$question_type_id=$qTypeRes['question_type_id'];
            //echo "This action is here :hasEditable".$question_id;
            //die(121234);
            $dataProviderF = new ActiveDataProvider([
                'pagination' => array('pageSize' => 5),
                 //'pagination' => false,
                'query' => CrmSurveyResponseChoice::find()->where(['question_id'=>$question_id])->orderby('id ASC'),
                'sort' => false
            ]);
  //var_dump( $dataProvider);
            return $this->renderAjax('response_choice_list', [
                'dataProvider' => $dataProviderF,
                'question_type_id'=>'0',
                // 'model' => $model,
            ]);
        }
  

         if (Yii::$app->request->post('hasNew')) {
            // die('new items');
            $model = new CrmSurveyResponseChoice();
            $model->load(Yii::$app->request->post());

           // $model->created_by = Yii::$app->user->identity->id;
            $hasError = true;
            $body = '';
            if($model->save()) {
                $hasError = false;
                $dataProvider = new ActiveDataProvider([
                    'pagination' => array('pageSize' => 5),
                    //'pagination' => false,
                    'query' => CrmSurveyResponseChoice::find()->where(['question_id'=>$model->question_id])->orderby('id ASC'),
                    'sort' => false
                ]);

                $body = $this->renderAjax('response_choice_list', [
                    'dataProvider' => $dataProvider,
                     'question_type_id'=>'0',

                ]);
            }

            echo Json::encode(['hasError'=>$hasError, 'body'=>$body]);
            return;
        }
        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           //return $this->redirect(['view', 'id' => $model->id]);
        } else {
           // return $this->render('create', [
           //     'model' => $model,
           // ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CrmSurveyResponseChoice::find(),
        ]);
        // die('Is it hee');
        return $this->render('response_choice_form', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'question_type_id'=>'0',

        ]);
        
    }
    
    
    public function actionIndex()
    {
        
        
         if (Yii::$app->request->post('hasEditable')) {
            //echo "This action is here :hasEditable";
           // die(121234);
            $id = Yii::$app->request->post('editableKey');
            $model = CrmSurveyResponseChoice::findOne($id);
            $message = '';

            $post = [];
            $posted = current($_POST['CrmSurveyResponseChoice']);
            $post['CrmSurveyResponseChoice'] = $posted;

            if ($model->load($post)) {
                if(!$model->save())
                    $message = ' ';
            }
            echo Json::encode(['output'=>'', 'message'=>$message]);
            return;
        }
        
              $model = new CrmSurveyResponseChoice();
        
          if (Yii::$app->request->get('refresh-widget')) {
            //echo "This action is here :hasEditable";
            // die(121234);
            $question_id = Yii::$app->request->get('question_id');
            //echo "This action is here :hasEditable".$question_id;
            //die(121234);
            $dataProviderF = new ActiveDataProvider([
               // 'pagination' => array('pageSize' => 5),
                'query' => CrmSurveyResponseChoice::find()->where(['question_id'=>$question_id])->orderby('id ASC'),
                'sort' => false
            ]);
  //var_dump( $dataProvider);
            return $this->renderAjax('index', [
                'dataProvider' => $dataProviderF,
                  'model' => $model,
            ]);
        }
  

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           //return $this->redirect(['view', 'id' => $model->id]);
        } else {
           // return $this->render('create', [
           //     'model' => $model,
           // ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CrmSurveyResponseChoice::find(),
        ]);
        // die('Is it hee');
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single CrmSurveyResponseChoice model.
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
     * Creates a new CrmSurveyResponseChoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CrmSurveyResponseChoice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CrmSurveyResponseChoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //die('PUKA');
            if (Yii::$app->request->get('hasToggle')) {
            $state = Yii::$app->request->get('state');
            $id = Yii::$app->request->get('id');
            $saved = false;
            if(isset($state) && isset($id)) {
               // $model = City::findOne($id);
                $model->is_deleted = ($state == 'true')?0:1;
                if($model->save()){
                    $saved = true;
                }
            }
            echo Json::encode(['saved'=>$saved]);
            return;
        }
       /* if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }*/
    }

    /**
     * Deletes an existing CrmSurveyResponseChoice model.
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
     * Finds the CrmSurveyResponseChoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmSurveyResponseChoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CrmSurveyResponseChoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
       public function actionPopulateqtype()
    {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $question_id = $parents[0];
            if ($question_id != null) {
                $out = self::getOc2($question_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    
       protected function getOc2 ($qid) {
              //save textbox
       $chkQ= "SELECT
    `crm_survey_question_type`.`id`
    , `crm_survey_question_type`.`name`
    , `crm_survey_question`.`id`
FROM
    `crm_survey_question`
    INNER JOIN `crm_survey_question_type` 
        ON (`crm_survey_question`.`question_type_id` = `crm_survey_question_type`.`id`) WHERE `crm_survey_question`.`id`=$qid" ;
      $textTypeQs= Yii::$app->db->createCommand($chkQ)->queryAll();
        return $textTypeQs;
    }
}
