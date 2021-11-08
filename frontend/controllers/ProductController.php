<?php

namespace frontend\controllers;

use Yii;
use frontend\models\product\Product;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;
use frontend\models\product\DailyUploadProduct;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use frontend\models\Logadm;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update','daily-product-update'],
                'rules' => [
                    [
                       'actions' => ['index', 'view'],
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
                            if(!Yii::$app->user->can('Create Product'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);

                            return Yii::$app->request->isAjax;
                       }
                    ],
                    [
                       'actions' => ['update'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Update Product'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationErrorAction']);

                            return Yii::$app->request->isAjax;
                       }
                    ],
					            [
                       'actions' => ['daily-product-update'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                               if(!Yii::$app->user->can('Update Contact'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                       }
                    ],
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
		

		
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    { 
       $model = $this->findModel($id);
        $logmodel= new Logadm();

           $data = array('Customer CIF' => $model->cif);
                  $data=json_encode($data);
      $logmodel->createViewLog("Product: ".$model->product_name,$data);
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($customer_id)
    {
        $model = new Product();
        $model->customer_id = $customer_id;
        $model->created_by = Yii::$app->user->identity->id;
		$time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
        $mystr = $time->format('Y-m-d h:i:s');
        $model->created_by_datetime=$mystr;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
          $data=Yii::$app->request->post();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->save()) {

 $logmodel= new Logadm();
            $logmodel->createInsertLog($data,$model);
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
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldData= $model->getOldAttributes();        
        $oldData['account_number']=$model->decryptString($oldData['account_number']);
        $time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
        $mystr = $time->format('Y-m-d h:i:s');
        $model->last_upated_datetime=$mystr;
		$model->last_updated_by=Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
             $data=Yii::$app->request->post();
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->save()) {

             $logmodel= new Logadm();
             $logmodel->createUpdateLog($oldData,$data,$model);
 
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

  public function actionDailyProductUpdate(){
	    $model = new DailyUploadProduct();
      $fileName="";
        if ($model->load(Yii::$app->request->post()) ) {
            $data=Yii::$app->request->post();
            $file = UploadedFile::getInstance($model, 'file');

            if ( $file )
                {
					//die('here');
                    $time = time();
					$date_upload = date('Ymdhi');
			        $filename = "MNL_Uploaded_Product_File_" . $date_upload ."_".$file->baseName. "." . $file->extension;
               $fileName=$filename;
                    $file->saveAs('uploads/' .$filename);
                    $file = 'uploads/' .$filename;
					
					
					//$sql2 = "LOAD DATA INFILE '".$file."'  INTO TABLE `test` FIELDS TERMINATED BY ' ' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n' (`type` , `command` , `value`)";
					//$query = Yii::$app->db->createCommand($sql2)->execute();
					
					
                     //die(  $file);
					 
					 $sql1 = "TRUNCATE  TABLE `product_temp`";
                    Yii::$app->db->createCommand($sql1)->execute();
					 
					 

                     $handle = fopen($file, "r");
					 $headers = fgetcsv($handle, 1000, ",");// this line helps to avoid headers
					 $count=0;
					 $resultData = [];
					 $saveMessage="";
					 
					 
					 
                     while (($fileop = fgetcsv($handle, 1000, ",")) !== false) 
                     {
						//$saveMessage="";
						$accountNumber = $fileop[0];
						$productName=$fileop[1];
						$accountLimit=$fileop[2];
						$accountStatus=$fileop[3];
						$branchname=$fileop[4];
						$digitalProducts=$fileop[5];
						$cif=$fileop[7];
						$relationship=$fileop[6];
						$nic=$fileop[8];
						$currency=$fileop[9];
						
						$checkProductExsists=$this->productAlreadyExist($cif,$accountNumber);
						//echo  'checkProductExsists='.strlen($checkProductExsists)."</br>";
						if (strlen($checkProductExsists)==0){
						
							$saveMessage = "Product sucessfully added";
							
						}
						else 
						{
						    $saveMessage = "Product sucessfully updated";
	
						}
						
						$sql = "INSERT INTO  `product_temp` (`account_number` , `product_name` , `account_limit`,account_status,branch_name,digital_products,relationship,cif,nic,currency)
					 VALUES ('$accountNumber','$productName','$accountLimit','$accountStatus','$branchname',' $digitalProducts','$relationship','$cif','$nic','$currency')";
                     Yii::$app->db->createCommand($sql)->execute();
						
						
                        $count = $count + 1;
                        $singleResult = array(
                       'SL' => $count,
                       'Account Number' => $accountNumber,
                       'Product Name' =>$productName,
					   'Account Limit'=>$accountLimit,
					   'Branch Name'=>$branchname,
                       'Account Status' =>$accountStatus ,
                       'Digital Products' => $digitalProducts,
                       'Relationship' =>$relationship,
					             'cif'=>$cif,
                       'Status' => $saveMessage,
						);
						array_push($resultData, $singleResult);
					
						
                     }

			
                     // if ($query) 
                     // {
                        // echo "data upload successfully";
                     // }

                }

             $modelData = $this->prepareUploadedResult($resultData);
			 //var_dump( $modelData );

            $logmodel= new Logadm();
            // var_dump($model);
            // die();
              $logmodel->createUpdateLogRule("FileUploadProduct: ","",$fileName,"INSERT");
           
			 return $this->render('daily_product_update_form',['model' => $model, 'resultData' => $modelData]);
        } else {
            return $this->render('daily_product_update_form', [
                'model' => $model,
            ]);
        }
	  
   }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
	 
	protected function productAlreadyExist($cif,$account_number) {

   $connection = Yii::$app->getDb();
   $qry="SELECT  id    FROM product WHERE cif = '$cif' and account_number = '$account_number' ";
   //echo $qry."</br>";
   $command = $connection->createCommand($qry);
   $result = $command->queryAll();
   if (count($result) > 0) {
           // foreach ($result as $modelData) {
                // $value = $modelData['id'];
           // }
			return true;
       } else {
           return false;
       }
   }
    protected function prepareUploadedResult($modeldata) {
       $resultView = new ArrayDataProvider([
           'allModels' => $modeldata,
           'pagination' => false,
           'sort' => [
               'attributes' => ['account_number', 'cif Name'],
           ],
       ]);
      // print_r($modeldata);
       return $resultView;
   }
	 
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
			$model->account_number = $model->decryptString($model->account_number);
			$model->card_number = $model->decryptString($model->card_number);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
