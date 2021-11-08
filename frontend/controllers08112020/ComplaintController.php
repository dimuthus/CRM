<?php

namespace frontend\controllers;

use Yii;
use frontend\models\complaint\Complaint;
use frontend\models\complaint\ComplaintSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\helpers\Json;

/**
 * ComplaintController implements the CRUD actions for Complaint model.
 */
class ComplaintController extends Controller
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
     * Lists all Complaint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Complaint::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Complaint model.
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
     * Creates a new Complaint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($customer_id)
    {
        $model = new Complaint();
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
     * Updates an existing Complaint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Deletes an existing Complaint model.
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
     * Finds the Complaint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Complaint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Complaint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //Added By Ahsan for Dropdown Management Tools on 18 September 2015 12:03pm
    public function actionPopulatebrandddl()
    {
          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            
            $parents = $_POST['depdrop_parents'];
            $division_id = $parents[0];
            //var_dump($case_id);
            //die(1234214);
            if ($division_id != null) {
                $out = self::getbrandddl($division_id); 
                  foreach ($out as $i => $val) {
                    $selected=$val['id'];
                   // echo $val['id'];
                }
              //  echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
               // die(32452345);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    //Added By Ahsan for Dropdown Management Tools on 18 September 2015 12:03pm
    protected function getBrandddl ($division_id) {
        $query = new Query;
        $query->select('id, name')
            ->from('complaint_brand')
            ->where('deleted = 0 and division_id = '.$division_id)
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
    
    //Added By Ahsan for Dropdown Management Tools on 18 September 2015 12:03pm
    public function actionPopulatesubbrandddl()
    {          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {            
            $parents = $_POST['depdrop_parents'];
            $brand_id = $parents[0];
            if ($brand_id != null) {
                $out = self::getSubbrandddl($brand_id); 
                  foreach ($out as $i => $val) {
                    $selected=$val['id'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    //Added By Ahsan for Dropdown Management Tools on 18 September 2015 12:03pm
    protected function getSubbrandddl ($brand_id) {
        $query = new Query;
        $query->select('id, name')
            ->from('complaint_subbrand')
            ->where('deleted = 0 and brand_id = '.$brand_id)
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
    
    //Added By Ahsan for Dropdown Management Tools on 18 September 2015 12:03pm
    public function actionPopulateproductddl()
    {          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {            
            $parents = $_POST['depdrop_parents'];
            $subbrand_id = $parents[0];
            if ($subbrand_id != null) {
                $out = self::getProductddl($subbrand_id); 
                  foreach ($out as $i => $val) {
                    $selected=$val['id'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
    //Added By Ahsan for Dropdown Management Tools on 18 September 2015 12:03pm
    protected function getProductddl ($subbrand_id) {
        $query = new Query;
        $query->select('id, name')
            ->from('complaint_product')
            ->where('deleted = 0 and subbrand_id = '.$subbrand_id)
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
     
      public function actionPopulatesubbrand()
    {
          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $case_id = $parents[0];
            if ($case_id != null) {
                $out = self::getSubbrand($case_id); 
                    foreach ($out as $i => $val) {
                    $selected=$val['id'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                 return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    
  public function actionPopulateproduct()
    {
          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $case_id = $parents[0];
            if ($case_id != null) {
                $out = self::getProduct($case_id); 
                      foreach ($out as $i => $val) {
                    $selected=$val['id'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    
    
    public function actionPopulatepacksize()
    {
          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $case_id = $parents[0];
            if ($case_id != null) {
                $out = self::getPacksize($case_id); 
                  foreach ($out as $i => $val) {
                    $selected=$val['id'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    
        public function actionPopulatecolor()
    {
          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $product_id = $parents[0];
            if ($product_id != null) {
                $out = self::getColor($product_id); 
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    
    
      public function actionPopulatebrand()
    {
          
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $case_id = $parents[0];
            if ($case_id != null) {
                $out = self::getbrand($case_id); 
                  foreach ($out as $i => $val) {
                    $selected=$val['id'];
                }
                echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);

    }
    
    
    
   protected function getBrand ($case_id) {
        $query = new Query;
        $query->select('`complaint_brand`.id, `complaint_brand`.name')
            ->from('cases')
            ->join('INNER JOIN', 'complaint_brand','`cases`.`brand_id` = `complaint_brand`.`id`')
            ->where('`cases`.`case_id`= '."'".$case_id."'")
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
     protected function getSubbrand ($case_id) {
        $query = new Query;
        $query->select('`complaint_subbrand`.id, `complaint_subbrand`.name')
            ->from('cases')
            ->join('INNER JOIN', 'complaint_subbrand','`cases`.`subbrand_id` = `complaint_subbrand`.`id`')
            ->where('`cases`.`case_id`= '."'".$case_id."'")
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
    
      protected function getProduct ($case_id) {
        $query = new Query;
        $query->select('`complaint_product`.id, `complaint_product`.name')
              ->from('cases')
            ->join('INNER JOIN', 'complaint_product','`cases`.`product_id` = `complaint_product`.`id`')
            ->where('`cases`.`case_id`= '."'".$case_id."'")
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
    
        protected function getPacksize($case_id) {
        $query = new Query;
        $query->select('`complaint_packsize`.id, `complaint_packsize`.name')
              ->from('cases')
            ->join('INNER JOIN', 'complaint_packsize','`cases`.`packsize_id` = `complaint_packsize`.`id`')
            ->where('`cases`.`case_id`= '."'".$case_id."'")
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
        protected function getColor($product_id) {
        $query = new Query;
        $query->select('id, name')
            ->from('complaint_color')
            ->where('deleted = 0 and product_id = '.$product_id)
            ->orderBy('name');
        $rows = $query->all();

        return $rows;
    }
  
}
