<?php
    
    namespace frontend\controllers;
    
    use Yii;
    use yii\data\ActiveDataProvider;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;
    use yii\filters\VerbFilter;
    use yii\web\Response;
    use yii\web\UploadedFile;
    use yii\db\Query;
    use yii\helpers\Json;
    
    use frontend\models\cases\CustomerCases;
    use frontend\models\interaction\InboundInteraction;
	use frontend\models\outboundInteraction\OutboundInteraction;
    use frontend\models\Logadm;
    use frontend\models\survey\SurveyResponse;
    use frontend\models\CaseSearch;
    use frontend\models\jmccrypt\JmcCrypt;
    use frontend\models\CasesSearch;

    
    /**
     * CasesController implements the CRUD actions for Cases model.
     */
    class CasesController extends Controller
    {
        
        public $file;
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
         * Lists all Cases models.
         * @return mixed
         */
        
        public function actionIndex()
        {
            
			
			if (!isset(Yii::$app->user->identity->id))
				return $this->redirect(['user/login'])->send();
			
           $searchModel =new CasesSearch();
            //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $query = CustomerCases::find()
            //->where('user.id != 1 AND customer_cases.case_status NOT IN (2,4)')
			//->andWhere(' customer_cases.case_status NOT IN (2)')

            ->joinWith('customer')
            //->joinWith('caseType')
            ->joinWith('caseStatus')
            ->joinWith([
                       'escalatedTo' => function ($q) {
                       $q->from('user es');
                       },
                       ])
           // ->joinWith('priority')
            ->joinWith('createdBy');
            
                       
            //echo $query->createCommand()->sql;
           // die('dddddd');
            if(!Yii::$app->user->can('View Service Reuqests Created by All Agents')){
                $query->andFilterWhere(['or',['customer_cases.created_by'=> Yii::$app->user->identity->id],['customer_cases.escalated_to'=> Yii::$app->user->identity->id]]);
			//->orFilterWhere(['customer_cases.escalated_to'=> Yii::$app->user->identity->id]);
			}
             //echo $query->createCommand()->sql;
             //die('user'.Yii::$app->user->identity->id);
            $dataProvider = new ActiveDataProvider([
                                                   'pagination' => ['pageSize'=>10],
                                                   'query' => $query,
                                                   'sort' => [
                                                   'defaultOrder' => ['created_datetime'=>SORT_DESC],
                                                   'attributes' => [
                                                   'customer.first_name' => [
                                                   'asc' => ['customer.first_name' => SORT_ASC],
                                                   'desc' => ['customer.first_name' => SORT_DESC],
                                                   'default' => SORT_ASC
                                                   ],
                                                   'customer.last_name' => [
                                                   'asc' => ['customer.last_name' => SORT_ASC],
                                                   'desc' => ['customer.last_name' => SORT_DESC],
                                                   'default' => SORT_ASC
                                                   ],
                                                   'case_id',
                                                   /*'caseType.name' => [
                                                    'asc' => ['case_type.name' => SORT_ASC],
                                                    'desc' => ['case_type.name' => SORT_DESC],
                                                    'default' => SORT_ASC
                                                    ],*/
                                                   'caseStatus.name' => [
                                                   'asc' => ['case_status.name' => SORT_ASC],
                                                   'desc' => ['case_status.name' => SORT_DESC],
                                                   'default' => SORT_ASC
                                                   ],
                                                   'escalatedTo.username' => [
                                                   'asc' => ['es.username' => SORT_ASC],
                                                   'desc' => ['es.username' => SORT_DESC],
                                                   'default' => SORT_ASC
                                                   ],
                                                   /*  'priority.name' => [
                                                    'asc' => ['case_priority.name' => SORT_ASC],
                                                    'desc' => ['case_priority.name' => SORT_DESC],
                                                    'default' => SORT_ASC
                                                    ],*/
                                                   'createdBy.username' => [
                                                   'asc' => ['user.username' => SORT_ASC],
                                                   'desc' => ['user.username' => SORT_DESC],
                                                   'default' => SORT_ASC
                                                   ],
                                                   'created_datetime' => [
                                                   'asc' => ['customer_cases.created_datetime' => SORT_ASC],
                                                   'desc' => ['customer_cases.created_datetime' => SORT_DESC],
                                                   'default' => SORT_DESC
                                                   ],
                                                   ]
                                                   ]
                                                   ]);

            $logmodel= new Logadm();
           $data = array('case_id' => "index");
                  $data=json_encode($data);
      $logmodel->createViewLog("Cases",$data);
            return $this->render('cases', [
                                 'searchModel' => $searchModel,
                                 'dataProvider' => $dataProvider,
                                 ]);
        }
        
        
        public function actionSearch()
        {
            $params =Yii::$app->request->queryParams;
            
            $searchModel = new CasesSearch();
            $results = $searchModel->search($params);
            // print_r($results);
            // die();

            
            return $this->renderAjax('case_list', [
                                     'dataProvider' => $results,
                                     ]);
            
        }
        
        
        /**
         * Displays a single Cases model.
         * @param integer $id
         * @return mixed
         */
        public function actionView($id)
        {   

            $logmodel= new Logadm();
           $data = array('case_id' => $id);
                  $data=json_encode($data);
      $logmodel->createViewLog("Case:View",$data);

			$campaign_id=0;
            if(isset($_GET['campaign_id']))
             $campaign_id=$_GET['campaign_id'];
            else
             $campaign_id=0;
            return $this->renderAjax('view', [
                                     'model' => $this->findModel($id),'campaign_id'=>$campaign_id
                                     ]);
        }
        
        /**
         * Creates a new Cases model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate($customer_id)
        {
            
            $model = new CustomerCases();
            //get the instance of uploaded file
            
            $model->customer_id = $customer_id;
            $model->created_by = Yii::$app->user->identity->id;
            //   $cus_id=$model->customer_id;
            $campaign_id=0;
			$product_id=0;
            if(isset($_GET['campaign_id']))
             $campaign_id=$_GET['campaign_id'];
            else
             $campaign_id=0;
		    
			 if(isset($_GET['product_id']))
             $product_id=$_GET['product_id'];
            else
             $product_id=0;
		    
            $model->product_id=$product_id;
            
            // && Yii::$app->request->isAjax
             if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                $data=Yii::$app->request->post();
                // $a=$model->escalated_to;
                //die("escalated_to");
                Yii::$app->response->format = Response::FORMAT_JSON;
                //$model->first_call_resolution = $model->case_status;
                 $model->myfile = UploadedFile::getInstances($model, 'myfile');
				 
				$time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
                $mystr = $time->format('Y-m-d h:i:s');
                $model->created_datetime=$mystr;
				
                foreach ($model->myfile as $file) {
                    $time = new \DateTime('now');
                    //$mystr = $time->format('Ymdhis');
                    $mystr =round(microtime(true)).rand(1000,9999);
                    $ori_filename=$file->name;
                    $model->attachment_name= $model->attachment_name."#LRL#".$ori_filename;
                    $c_filename=$mystr."-".$customer_id.".".$file->extension;
                    $file->saveAs( 'uploads/'. $c_filename);
                    $model->attachment_url=$model->attachment_url."#LRL#".'uploads/'.$c_filename;
                    //echo "<br/>".$model->attachment_url;
                }
                // print_r($model->getErrors());
                if($model->save()) {
                    //Yii::$app->response->format = Response::FORMAT_JSON;
                    $logmodel=new Logadm();
                     $logmodel->createInsertLog($data,$model);
                    $case_id= $model->getPrimaryKey();
                    $case_status=$model->case_status;
                    $model = new InboundInteraction();
                    $model->created_by = Yii::$app->user->identity->id;
                    $model->case_tbl_id = $case_id;
                    //$model->case_status = $case_status;
                                        Yii::$app->session->setFlash('success', "Case sucessfully created."); 

					if ( $campaign_id ==0){
                    $body = $this->renderAjax('//inbound-interaction/create', ['model' => $model]);
					}
				    else{
					$model= new OutboundInteraction();
                    $model->created_by = Yii::$app->user->identity->id;
                    $model->case_tbl_id = $case_id;    
					$body = $this->renderAjax('//outbound-interaction/create', ['model' => $model]);
					}

                    //$body = $this->renderAjax('view', ['model' => $this->findModel($model->getPrimaryKey()),]);
                    $hasError = false;
                    
                }
                else {
                    //var_dump($_POST);
                    
                    $body = $this->renderAjax('create', ['model' => $model, 'campaign_id'=>$campaign_id]);
                    $hasError = true;
                }
                return ['body' => $body,
                'hasError' => $hasError,
                'id' => $model->id,];
                
            } else {
              // die('dddddddddd');
                return $this->renderAjax('create', [
                                         'model' => $model,
                                         'campaign_id'=>$campaign_id
                                         ]);
            }
        }
        
        /**
         * Updates an existing Cases model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param integer $id
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = $this->findModel($id);
            $oldData= $model->getOldAttributes();

            $campaign_id=0;
            if(isset($_GET['campaign_id']))
             $campaign_id=$_GET['campaign_id'];
            else
             $campaign_id=0;



            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                //die('crerated update updating issue'.date_default_timezone_get());
                $model->last_updated_by=Yii::$app->user->identity->id;
                $time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
                $mystr = $time->format('Y-m-d h:i:s');
                $model->last_updated_datetime=$mystr;
                $model->myfile = UploadedFile::getInstances($model, 'myfile');
                foreach ($model->myfile as $file) {

                               echo "Its here...!!!";
                              // die(234254);
                               $time = new \DateTime('now');
                               //$mystr = $time->format('Ymdhis');
                               $mystr =round(microtime(true)).rand(1000,9999);
                               $ori_filename=$file->name;
                               $model->attachment_name= $model->attachment_name."#LRL#".$ori_filename;
                               $c_filename=$mystr."-".$model->customer_id.".".$file->extension;
                               $file->saveAs( 'uploads/'. $c_filename);
                               $model->attachment_url=$model->attachment_url."#LRL#".'uploads/'.$c_filename;
                               //echo "<br/>".$model->attachment_url;
                }
                
                if($model->save()) {
                            $data=Yii::$app->request->post();
             // var_dump($dataUpdate);
            
             $logmodel= new Logadm();
            $logmodel->createUpdateLog($oldData,$data,$model);
                    $model = $this->findModel($model->getPrimaryKey());
                    $case_id= $model->getPrimaryKey();
                    
                    $jmcIns = new JmcCrypt();
                    $hashID= $jmcIns->HashMe($model->customer_id);
                    
                    $this->redirect(array('//customer/'.$model->customer_id.'?jmc='.$hashID,[]));
                    $hasError = false;
                    
                    
                    
                }
                else {
                    $body = $this->renderAjax('update', ['model' => $model,'campaign_id'=>$campaign_id]);
                    $hasError = true;
                }
                return ['body' => $body,
                'hasError' => $hasError,
                'id' => $model->id,];
                
            } else {
                return $this->renderAjax('update', [
                                         'model' => $model,'campaign_id'=>$campaign_id
                                         ]);
            }
        }
        
        /**
         * Deletes an existing Cases model.
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
         * Finds the Cases model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return Cases the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = CustomerCases::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        
        public function actionPopulateoc2()
        {
            
            $out = [];
            if (isset($_POST['depdrop_parents'])) {
                $parents = $_POST['depdrop_parents'];
                $outcomecode1_id = $parents[0];
                if ($outcomecode1_id != null) {
                    $out = self::getOc2($outcomecode1_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            
        }
        
        public function actionPopulateoc3()
        {
            
            $out = [];
            if (isset($_POST['depdrop_parents'])) {
                $parents = $_POST['depdrop_parents'];
                $outcomecode2_id = $parents[0];
                if ($outcomecode2_id != null) {
                    $out = self::getOc3($outcomecode2_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            
        }
		
   public function actionPopulateoc1outbound()
    {

        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $channel_id = $parents[0];
            if ($channel_id != null) {
                $out = self::getOc1Outbound($channel_id);
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
                $devision_id = $parents[0];
                if ($devision_id != null) {
                    $out = self::getBrand($devision_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            
        }
        
        public function actionPopulatesubbrand()
        {
            
            $out = [];
            if (isset($_POST['depdrop_parents'])) {
                $parents = $_POST['depdrop_parents'];
                $brand_id = $parents[0];
                if ($brand_id != null) {
                    $out = self::getSubbrand($brand_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
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
                $subbrand_id = $parents[0];
                if ($subbrand_id != null) {
                    $out = self::getProduct($subbrand_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
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
                $product_id = $parents[0];
                if ($product_id != null) {
                    $out = self::getPacksize($product_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
            
        }
        
          protected function getOc1Outbound ($channel) {
            if ($channel==1)
                $inbound=0;
            else
                 $inbound=1;
            $query = new Query;
            $query->select('id, name')
            ->from('outcome_code1')
            ->where('deleted = 0 and inbound = '.$inbound)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }
        
        
        protected function getOc2 ($oc1) {
            $query = new Query;
            $query->select('id, name')
            ->from('outcome_code2')
            ->where('deleted = 0 and outcome_code1_id = '.$oc1)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }
        
        
        protected function getOc3 ($oc2) {
            $query = new Query;
            $query->select('id, name')
            ->from('outcome_code3')
            ->where('deleted = 0 and outcome_code2_id = '.$oc2)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }

    
   
        protected function getBrand ($division_id) {
            $query = new Query;
            $query->select('id, name')
            ->from('complaint_brand')
            ->where('deleted = 0 and division_id = '.$division_id)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }
        protected function getSubbrand ($brand_id) {
            $query = new Query;
            $query->select('id, name')
            ->from('complaint_subbrand')
            ->where('deleted = 0 and brand_id = '.$brand_id)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }
        
        protected function getProduct ($subbrand_id) {
            $query = new Query;
            $query->select('id, name')
            ->from('complaint_product')
            ->where('deleted = 0 and subbrand_id = '.$subbrand_id)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }
        
        protected function getPacksize($product_id) {
            $query = new Query;
            $query->select('id, name')
            ->from('complaint_packsize')
            ->where('deleted = 0 and product_id = '.$product_id)
            ->orderBy('name');
            $rows = $query->all();
            
            return $rows;
        }
        
        public function actionLoadinteractions($case_id)
        {
            $dataProvider = new ActiveDataProvider([
                                                   'pagination' => array('pageSize' => 3),
                                                   'query' => InboundInteraction::find()->where(['case_tbl_id' => $case_id])
                                                   // ->joinWith('channel')
                                                   ->joinWith('creator'),
                                                   'sort' => [
                                                   'defaultOrder' => ['created_datetime'=>SORT_DESC],
                                                   'attributes' => [
                                                   'interaction_id',
                                                   /*    'channel.name' => [
                                                    'asc' => ['interaction_channel_type.name' => SORT_ASC],
                                                    'desc' => ['interaction_channel_type.name' => SORT_DESC],
                                                    'default' => SORT_DESC
                                                    ],*/
                                                   'creator.username' => [
                                                   'asc' => ['user.username' => SORT_ASC],
                                                   'desc' => ['user.username' => SORT_DESC],
                                                   'default' => SORT_DESC
                                                   ],
                                                   'created_datetime'
                                                   ]
                                                   ],
                                                   ]);
            
            return $this->renderAjax('interactionList', [
                                     'interactions' => $dataProvider,
                                     'case_id' => $case_id
                                     ]);
        }
        
        public function actionLoadcases($product_id, $customer_id){
            
            $cases = new ActiveDataProvider([
                                            'pagination' => ['pageSize' => 3],
                                            'query' =>Cases::find()->where(['customer_id' => $customer_id, '$product_id'=>$product_id])
                                            ->joinWith('caseStatus'),
                                            'sort' => [
                                            'defaultOrder' => ['created_datetime'=>SORT_DESC],
                                            'attributes' => [
                                            'case_id',
                                            'caseStatus.name' => [
                                            'asc' => ['case_status.name' => SORT_ASC],
                                            'desc' => ['case_status.name' => SORT_DESC],
                                            'default' => SORT_DESC
                                            ],
                                            
                                            'created_datetime'
                                            ]
                                            ]
                                            ]);
            
            return $this->render(Url::to('../cases/index'), [
                                 'cases' => $cases,
                                 ]);
            
            
            //die("hello");
            
        }
        
    }

