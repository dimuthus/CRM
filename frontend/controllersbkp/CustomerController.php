<?php

namespace frontend\controllers;

use Yii;
use frontend\models\customer\Customer;
use frontend\models\customer\EmailLog;
use frontend\models\customer\Remoteuploaderlog;
use frontend\models\product\Product;
use frontend\models\cases\CustomerCases;
use frontend\models\customer\UploadCustomer;
use frontend\models\customer\UploadCustomerremote;
use frontend\models\interaction\InboundInteraction;
use frontend\models\outboundInteraction\OutboundInteraction;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;
use frontend\models\jmccrypt\JmcCrypt;

use frontend\models\customerEvents\CustomerEvents;
use frontend\models\campaign\CustomerCampaign;
/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'viewdetails', 'create', 'update','getcustomerybymrn','campagindataupload','daily-customer-update'],
                'rules' => [
                    [
                       'actions' => ['view'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('CRM Interface Page'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ],
                    [
                       'actions' => ['create'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Create Contact'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ],
                    [
                       'actions' => ['update'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Update Contact'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ],
                    [
                       'actions' => ['viewdetails'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Yii::$app->request->isAjax;
                       }
                    ],
                             [
                       'actions' => ['getcustomerybymrn'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                               if(!Yii::$app->user->can('Update Contact'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                       }
                    ],
					   [
                       'actions' => ['campagindataupload','daily-customer-update'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                               if(!Yii::$app->user->can('Update Contact'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                       }
                    ]
					
               ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]
        ];
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$jmc)
    {
		
      $jmcIns = new JmcCrypt();
      $getID= Yii::$app->request->get('id');
      $getjmc= Yii::$app->request->get('jmc');
      $request_hash= $jmcIns->HashMe($getID);

      /*echo $jmc.'<br/>'; //------testing purpose---
      echo 'ID=='.$id.'<br/>';
      echo 'GetID=='.$getID.'<br/>';
      echo 'new=='.$request_hash.'<br/>';
      die('Above is the hashing test result');*/

      // if any url tampering it will show bad request....
      // if ($jmc!=$request_hash)
        // $this->findModel('000');

          $cif=$this->getCustomerCif($id);


        $cases = new ActiveDataProvider([
            'pagination' => ['pageSize' => 3],
            'query' =>CustomerCases::find()->where(['customer_id' => $id])
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
     // var_dump($cases);

					$encryptionKey = Yii::$app->params['encryptionKey'];

	    $Products = new ActiveDataProvider([
            'pagination' => ['pageSize' => 3],
            'query' =>Product::find()->select(['id','product_name','AES_DECRYPT(UNHEX(`account_number`),"'.$encryptionKey.'") AS account_number'])->where(['cif' => $cif])


        ]);

//
//              $CustomerSurveys = new ActiveDataProvider([
//            'pagination' => ['pageSize' => 3],
//            'query' => CrmSurveyResponse::find()->where(['respondent_id' => $id])
//
//
//        ]);

              $interactions = new ActiveDataProvider([
            'query' => InboundInteraction::find()->where(['case_tbl_id' => -1])
            ->joinWith('createdBy'),
            'sort' => [
                'defaultOrder' => ['created_datetime'=>SORT_DESC],
                'attributes' => [
                    'inbound_interaction_id',
                     'createdBy.username' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                        'default' => SORT_DESC
                    ],
                    'created_datetime'
                ]
            ],
        ]);

              $outboundinteractions = new ActiveDataProvider([
            'query' => OutboundInteraction::find()->where(['customer_id' => $id])
            ->joinWith('createdBy'),
            'sort' => [
                'defaultOrder' => ['created_datetime'=>SORT_DESC],
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


        //this code handles loading the right service request
        $case_to_load = 0;
        $search_values = Yii::$app->session['case_id_to_load'];
        if(isset($search_values)) {
            $case_to_load = $search_values;
            $cases->pagination->pageSize = $cases->pagination->totalCount;
            $count = 0;
            foreach($cases->models as $record) {
                $count++;
                if($record->id ==  $case_to_load)
                    break;
            }

            $cases->pagination->pageSize = 3;
            $cases->refresh();
            Yii::$app->session->remove('case_id_to_load');
            $cases->pagination->page = ceil($count/3)-1;
        }
          $model= $this->findModel($id);
          // var_dump($model);
          // $model->mobile_number= $model->decryptString($model->mobile_number);
          // var_dump($model->mobile_number);

        return $this->render('view', [
            'model' => $model,
             'cases' => $cases,
             'products'=>$Products,
             'interactions' => $interactions,
             'case_to_load' => $case_to_load,
             'pass_customer_id' => $id,
             'customer_id' => $id,
             'outboundinteractions' => $outboundinteractions,
            // 'CustomerSurveys'=>$CustomerSurveys,
            // 'survey_response_id'=>'0',
            //'products'=>$products,

        ]);
    }

    public function actionViewdetails($id)
    {
        return $this->renderAjax('full_details', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Customer();
        $search_values = Yii::$app->session['search_values'];
        if (isset($search_values)) {
            $model->load($search_values);
            Yii::$app->session->remove('search_values');
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->created_by = Yii::$app->user->identity->id;

           // $model->updated_by = Yii::$app->user->identity->id;

			$time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
            $mystr = $time->format('Y-m-d h:i:s');
            $model->created_datetime=$mystr;

            if($model->save()) {
              $jmcIns = new JmcCrypt();
              $hashID= $jmcIns->HashMe($model->id);

                return $this->redirect(['view', 'id' => $model->id, 'jmc' => $hashID]);
            }
            else {
                return $this->render('create', [
                    'model' => $model,
            ]);}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$jmc)
    {
		$jmcIns = new JmcCrypt();
      $getID= Yii::$app->request->get('id');
      $getjmc= Yii::$app->request->get('jmc');
      $request_hash= $jmcIns->HashMe($getID);
        $model = $this->findModel($id);
		$time = new \DateTime('now', new \DateTimeZone('Asia/Colombo'));
        $mystr = $time->format('Y-m-d h:i:s');
        $model->last_updated_datetime=$mystr;
		$model->updated_by=Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $jmcIns = new JmcCrypt();
            $hashID= $jmcIns->HashMe($model->id);
            return $this->redirect(['view', 'id' => $model->id, 'jmc' => $hashID]);

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }






   protected function getEventId($event_name,$event_type,$event_location,$event_month,$event_year) {
     $connection = Yii::$app->getDb();
     $command = $connection->createCommand("
       SELECT  ev.id
       FROM events as ev
       INNER JOIN state AS st ON st.id = ev.event_location
       WHERE lcase(ev.event_name) = '$event_name' AND lcase(ev.event_type) = '$event_type'
       AND lcase(ev.event_month) = '$event_month' AND lcase(ev.event_year) = '$event_year'
       AND lcase(st.name) = '$event_location' ");

     $result = $command->queryAll();
     if (count($result) > 0) {
             foreach ($result as $modelData) {
                  $value = $modelData['id'];
             }
       return $value;
         } else {
             return false;
         }
     }



 public function encryptString($stringValues) {
        // Nothing to decrypt
        if ($stringValues == '')
            return '';
       	$encryptionKey = Yii::$app->params['encryptionKey'];
        return new \yii\db\Expression('HEX(AES_ENCRYPT("'.$stringValues.'","'.$encryptionKey.'" ))');
    }

   protected function isAlreadyExist($mobile_number) {
        $query = new Query;
	    $customerModel= new Customer();
		$encryptedMoblie = $this->encryptString($mobile_number);
        $query->select('id, mobile_number,alternative_number')->from('customer')->andFilterWhere(['mobile_number'=>$encryptedMoblie]);
	   //echo $query->createCommand()->getRawSql();
       //die('as');
       $rows = $query->all();
	   if (count($rows) > 0) {
           foreach ($rows as $modelData) {
            
				return $modelData['id'];
              }
			 }
			   else {
           return false;
       }	
			 
       
	 }
   

 protected function ccAlreadyExist($cus_id,$campaign_id) {

   $connection = Yii::$app->getDb();
   $command = $connection->createCommand("
     SELECT  id
     FROM customer_campaign
     WHERE customer_id = '$cus_id' and campaign_id = '$campaign_id' ");

   $result = $command->queryAll();
   if (count($result) > 0) {
           foreach ($result as $modelData) {
                $value = $modelData['id'];
           }
     return $value;
       } else {
           return false;
       }
   }

   protected function prepareUploadedResult($modeldata) {
       $resultView = new ArrayDataProvider([
           'allModels' => $modeldata,
           'pagination' => false,
           'sort' => [
               'attributes' => ['Mobile', 'First Name'],
           ],
       ]);
      // print_r($modeldata);
       return $resultView;
   }

   /**
     * Insert log for uploader
     * If the query got error error exception will be thrown.
     * @param text $filename
     * @return true or false
     * @throws Exception if the query not run
     */
    protected function uploader_log($filename) {

		$connection = new Query;
		try {
			$connection->createCommand()
			->insert('uploader_log', [
					'filename' => $filename,
					'uploaded_by'=> Yii::$app->user->identity->id,])
			->execute();
			return true;
		} catch (Exception $ex) {
			echo 'Query failed', $ex->getMessage();
			return false;
		}

    }

	/**
     * Insert customer update log
     * If the query got error error exception will be thrown.
     * @throws Exception if the query not run
     */
    protected function customerupdate_log($customer_id,$alternate_contact_number) {

		$connection = new Query;
		try {
			$connection->createCommand()
			->insert('customer_updation', [
					'customer_id' => $customer_id,
					'updated_fields' => $alternate_contact_number,
					'updated_by'=> Yii::$app->user->identity->id,])
			->execute();
			return true;
		} catch (Exception $ex) {
			echo 'Query failed', $ex->getMessage();
			return false;
		}

    }

	/**
     * Checking on scientific exponential notation (E+) value
     * If the mobile number got E+ value return true else false
     * @param text $mobileNo
     * @return true or false
     */
	protected function check_exp_notation($mobileNo) {

		if (strpos ( $mobileNo, "E+" ) === false) {
			return false;
		  } else {
			return true;
		  }
    }

    /*
    	* finding id from table
    	*
    	*/
    	protected function gettableid($table, $column, $filter_col, $value) {

        $value = strtolower($value);
    		$connection = Yii::$app->getDb();
    		$value = addslashes($value);
			//$qry="SELECT  $column	FROM $table WHERE lcase($filter_col) = '$value'";
			//echo $qry.";<br/>";
    		$command = $connection->createCommand("
    			SELECT  $column
    			FROM $table
    			WHERE lcase($filter_col) = '$value'");

    		$result = $command->queryAll();
    		if (count($result) > 0) {
                foreach ($result as $modelData) {
                     $value = $modelData['id'];
                }
    			return $value;
            } else {
                return 0;
            }
        }

//uploader code ends here....
    
	/*It is important that if pass parameter as $id, can just pass it http://10.1.27.231/boc_crm/customer/getcustomerybymrn/45 */
    public function actionGetcustomerybymrn($mrn) {
        $model = new Customer();
        if (isset(Yii::$app->user->identity->id)){
        $mrn= Yii::$app->request->get('mrn');
         $connection = Yii::$app->getDb();
         $qry="SELECT id FROM customer WHERE  mobile_number='$mrn'";
         $command = $connection->createCommand($qry);
         $result = $command->queryAll();
        if (count($result) > 0) {
             foreach ($result as $modelData) {
                  $customerId= $modelData['id'];
             }
            $model = $this->findModel($customerId);
            $jmcIns = new JmcCrypt();
            $hashID= $jmcIns->HashMe($model->id);
            return $this->redirect(['view', 'id' => $model->id, 'jmc' => $hashID]);
         } else {
              return $this->render('create', ['model' => $model,]);
         }
        
        }
        else{
            return $this->goHome();

        }
        
    }
   
   protected function findModel($id) {
        if (($model = Customer::findOne($id)) !== null) {
            //$model->frequent_flyer_id = $model->decryptString($model->frequent_flyer_id);
            $model->full_name = $model->decryptString($model->full_name);
	   // $model->middle_name = $model->decryptString($model->middle_name);
	   // $model->surname = $model->decryptString($model->surname);
            $model->mobile_number = $model->decryptString($model->mobile_number);
            //$model->contact_number = $model->decryptString($model->contact_number);
           $model->email = $model->decryptString($model->email);
           $model->t_pin = $model->decryptString($model->t_pin);
           // $model->customer_id = $model->decryptString($model->customer_id);
		   $model->passport = $model->decryptString($model->passport);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
	protected function findModelTest($id) {
        if (($model = Customer::findOne($id)) !== null) {
			
			$encryptionKey = Yii::$app->params['encryptionKey'];
			$model->full_name = (new Query)->select(['AES_DECRYPT(UNHEX(full_name), "'.$encryptionKey.'") as full_name FROM `customer` WHERE id='.$id.''])->scalar();
			$model->mobile_number = (new Query)->select(['AES_DECRYPT(UNHEX(mobile_number), "'.$encryptionKey.'") as mobile_number FROM `customer` WHERE id='.$id.''])->scalar();
			$model->t_pin = (new Query)->select(['AES_DECRYPT(UNHEX(t_pin), "'.$encryptionKey.'") as t_pin FROM `customer` WHERE id='.$id.''])->scalar();

			$model->passport = (new Query)->select(['AES_DECRYPT(UNHEX(passport), "'.$encryptionKey.'") as passport FROM `customer` WHERE id='.$id.''])->scalar();
			$model->email = (new Query)->select(['AES_DECRYPT(UNHEX(email), "'.$encryptionKey.'") as email FROM `customer` WHERE id='.$id.''])->scalar();

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	  public function actionCampaginDataUpload(){
		 		ini_set('memory_limit','2056M');

	    $model = new UploadCustomer();
        if ($model->load(Yii::$app->request->post()) ) {
            $file = UploadedFile::getInstance($model, 'file');
            $campain_id=$model->campaign_id;
            if ( $file )
                {
					//die('here');
                    $time = time();
					$date_upload = date('Ymdhi');
			        $filename = "File_".$campain_id."_" . $date_upload ."_".$file->baseName. "." . $file->extension;
                    $file->saveAs('uploads/' .$filename);
                    $file = 'uploads/' .$filename;
					
					
					// $sql2 = "LOAD DATA INFILE '".$file."'  INTO TABLE `test` FIELDS TERMINATED BY ' ' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n' (`type` , `command` , `value`)";

					// $query = Yii::$app->db->createCommand($sql2)->execute();
					//die(  $file);
                     $handle = fopen($file, "r");
					 $headers = fgetcsv($handle, 1000, ",");// this line helps to avoid headers
					 $count=0;
					 $resultData = [];
					 $saveMessage="";
                     while (($fileop = fgetcsv($handle, 1000, ",")) !== false) 
                     {
						 $customerName = $fileop[0];
						 $rmn= $fileop[1];
						 $customerMobile =$fileop[2];
						 $accountType = $fileop[3];
						 $accountNumber = $fileop[4];
						 $email = $fileop[5];
						 $digtalProduct =$fileop[6];
						 $cif =$fileop[7];
						 $field2 =$fileop[8];
						
											
                        $customer_id=$this->isAlreadyExist($rmn,$customerMobile);
						/*check customer already in the campain table*/
						$checkCampain=$this->ccAlreadyExist($customer_id,$campain_id);
						//echo 'checkCampain'.$checkCampain;
						if (!$checkCampain){
						
					     $customer_campaign = New CustomerCampaign();
						 $customer_campaign->customer_id=$customer_id;
						 $customer_campaign->campaign_id=$campain_id;
						 $customer_campaign->created_by=Yii::$app->user->identity->id;
						 if ($customer_campaign->save(false)){
								$saveMessage = "Added for the campaign";
							} else {
								$saveMessage = "Not Saved, Server Issue.";
							}	
						}
						else
						{
						    $saveMessage = "Customer already in the campaign";
	
						}
						$count = $count + 1;
                        $singleResult = array(
                       'SL' => $count,
                       'Full Name' => $customerName,
                       'Registered Contact' => $rmn,
                       'Mobile' => $customerMobile,
                       'Account Type' =>$accountType,
                       'Account Number' => $accountNumber,
                       'Email' =>$email,
                       'DigtalProduct' =>$digtalProduct ,
                       'field1' => $cif,
                       'field2' =>$field2,
                       'Status' => $saveMessage,
						);
						array_push($resultData, $singleResult);
                     }
					//exit();
                

                }

				$modelData = $this->prepareUploadedResult($resultData);
				$this->uploader_log($filename);//upload log
                return $this->render('upload', ['model' => $model, 'resultData' => $modelData]);
        } else {
               return $this->render('upload', ['model' => $model]);
           
        }
   }
protected function getCustomerId($cif){
       $query = new Query;
       $query->select('id, cif')->from('customer')->orderBy('id');
	   $rows = $query->all();
       if (count($rows) > 0) {
			foreach ($rows as $modelData) {
			$customerd= new Customer();
			if ($cif == $modelData['cif']) {
				return $modelData['id'];
              
            }
		}
       }
	   else {
           return false;
       }
   }  

protected function getCustomerCif($id){
   $connection = Yii::$app->getDb();
   $qry="SELECT  cif    FROM customer WHERE id = $id ";
   //echo $qry."</br>";
   $command = $connection->createCommand($qry);
   $result = $command->queryAll();
   if (count($result) > 0) {
           foreach ($result as $modelData) {
               return $modelData['cif'];
           }
			return true;
       } 
   }  
   
	protected function customerAlreadyExist($cif) {

   $connection = Yii::$app->getDb();
   $qry="SELECT  id    FROM customer WHERE cif = $cif ";
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

  public function actionDailyCustomerUpdate(){
	    $model = new UploadCustomer;
        if ($model->load(Yii::$app->request->post()) ) {

            $file = UploadedFile::getInstance($model, 'file');

            if ( $file )
                {
					//die('here');
                    $time = time();
					$date_upload = date('Ymdhi');
			        $filename = "MNL_Uploaded_customer_File_" . $date_upload ."_".$file->baseName. "." . $file->extension;
                    $file->saveAs('uploads/' .$filename);
                    $file = 'uploads/' .$filename;
					
					
					//$sql2 = "LOAD DATA INFILE '".$file."'  INTO TABLE `test` FIELDS TERMINATED BY ' ' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n' (`type` , `command` , `value`)";
					//$query = Yii::$app->db->createCommand($sql2)->execute();
					Yii::$app->db->createCommand("TRUNCATE  TABLE `customer_temp`")->execute();
					 
					 $handle = fopen($file, "r");
					 $headers = fgetcsv($handle, 1000, ",");// this line helps to avoid headers
					 $count=0;
					 $resultData = [];
					 $saveMessage="";
					 while (($fileop = fgetcsv($handle, 1000, ",")) !== false) 
                     {
						//$saveMessage="";

						
					
						$salutation_id=\Yii::$app->db->quoteValue($fileop[0]);
						$gender=\Yii::$app->db->quoteValue($fileop[1]);
						$full_name=\Yii::$app->db->quoteValue($fileop[2]);
						$preferred_name=\Yii::$app->db->quoteValue($fileop[3]);
						$new_nic=\Yii::$app->db->quoteValue($fileop[4]);
						$old_nic=\Yii::$app->db->quoteValue($fileop[5]);
						$cif=\Yii::$app->db->quoteValue($fileop[6]);
						$t_pin=\Yii::$app->db->quoteValue($fileop[7]);
						$marital_status=\Yii::$app->db->quoteValue($fileop[8]);
						$spouse_name=\Yii::$app->db->quoteValue($fileop[9]);
						$passport=\Yii::$app->db->quoteValue($fileop[10]);
						$driving_license=\Yii::$app->db->quoteValue($fileop[11]);
						$business_name=\Yii::$app->db->quoteValue($fileop[12]);
						$business_reg_number=\Yii::$app->db->quoteValue($fileop[13]);
						$business_registered_date=\Yii::$app->db->quoteValue($fileop[14]);
						$mobile_number=\Yii::$app->db->quoteValue($fileop[15]);
						$staff_pf=\Yii::$app->db->quoteValue($fileop[16]);
						$alternative_number=\Yii::$app->db->quoteValue($fileop[17]);
						$email=\Yii::$app->db->quoteValue($fileop[18]);
						$address1=\Yii::$app->db->quoteValue($fileop[19]);
						$address2=\Yii::$app->db->quoteValue($fileop[20]);
						$address3=\Yii::$app->db->quoteValue($fileop[21]);
						$town=\Yii::$app->db->quoteValue($fileop[22]);
						$district=\Yii::$app->db->quoteValue($fileop[23]);
						$postal_code=\Yii::$app->db->quoteValue($fileop[24]);
						$province=\Yii::$app->db->quoteValue($fileop[25]);
						$alternate_address1=\Yii::$app->db->quoteValue($fileop[26]);
						$alternate_address2=\Yii::$app->db->quoteValue($fileop[27]);
						$alternate_address3=\Yii::$app->db->quoteValue($fileop[28]);
						$alternate_town=\Yii::$app->db->quoteValue($fileop[29]);
						$alternate_district=\Yii::$app->db->quoteValue($fileop[30]);
						$alternate_postal_code=\Yii::$app->db->quoteValue($fileop[31]);
						$preferred_language=\Yii::$app->db->quoteValue($fileop[32]);
						$customer_since=\Yii::$app->db->quoteValue($fileop[33]);
						$citizenship=\Yii::$app->db->quoteValue($fileop[34]);
						$profession=\Yii::$app->db->quoteValue($fileop[35]);
						$employer=\Yii::$app->db->quoteValue($fileop[36]);
						$dob=\Yii::$app->db->quoteValue($fileop[37]);
						$branch=\Yii::$app->db->quoteValue($fileop[38]);
						$relationship_manager=\Yii::$app->db->quoteValue($fileop[39]);
						$customer_status=\Yii::$app->db->quoteValue($fileop[40]);
						$customer_type=\Yii::$app->db->quoteValue($fileop[41]);
						$vip_flag=\Yii::$app->db->quoteValue($fileop[42]);
						$group_code=\Yii::$app->db->quoteValue($fileop[43]);
						
						$checkCustomerExsists=$this->customerAlreadyExist($cif);
					   // echo  'checkCustomerExsists='.strlen($checkCustomerExsists)."</br>";
						if (strlen($checkCustomerExsists)==0){
						
							$saveMessage = "Customer sucessfully added";
							
						}
						else 
						{
						    $saveMessage = "Customer sucessfully updated";
	
						}
						
						
						$sql="INSERT INTO `customer_temp` (salutation_id,gender,full_name,preferred_name,new_nic,old_nic,cif,t_pin,marital_status,spouse_name,passport,driving_license,business_name,business_reg_number,business_registered_date,mobile_number,staff_pf,alternative_number,email,address1,address2,address3,town,district,postal_code,province,alternate_address1,alternate_address2,alternate_address3,alternate_town,alternate_district,alternate_postal_code,preferred_language,customer_since,citizenship,profession,employer,dob,branch,relationship_manager,customer_status,customer_type,vip_flag,group_code)
						VALUES (
						$salutation_id,
						$gender,
						$full_name,
						$preferred_name,
						$new_nic,
						$old_nic,
						$cif,
						$t_pin,
						$marital_status,
						$spouse_name,
						$passport,
						$driving_license,
						$business_name,
						$business_reg_number,
						$business_registered_date,
						$mobile_number,
						$staff_pf,
						$alternative_number,
						$email,
						$address1,
						$address2,
						$address3,
						$town,
						$district,
						$postal_code,
						$province,
						$alternate_address1,
						$alternate_address2,
						$alternate_address3,
						$alternate_town,
						$alternate_district,
						$alternate_postal_code,
						$preferred_language,
						$customer_since,
						$citizenship,
						$profession,
					    $employer,
						$dob,
						$branch,
						$relationship_manager,
						$customer_status,
						$customer_type,
						$vip_flag,
						$group_code)";
	
                        Yii::$app->db->createCommand($sql)->execute();
						
						
                        $count = $count + 1;
                        $singleResult = array(
                       'SL' => $count,
                        'salutation_id'=>$salutation_id,
						'gender'=>$gender,
						'full_name'=>$full_name,
						'preferred_name'=>$preferred_name,
					    'new_nic'=>$new_nic,
						'old_nic'=>$old_nic,
						'cif'=>$cif,
                       'Status' => $saveMessage,
						);
						array_push($resultData, $singleResult);
					
						
                     }
		//$sql="LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `customer_temp` FIELDS TERMINATED BY ',' ENCLOSED BY '' LINES TERMINATED BY '\r\n' IGNORE 1 LINES 
        //  (salutation_id,gender,full_name,preferred_name,new_nic,old_nic,cif,t_pin,marital_status,spouse_name,passport,driving_license,business_name,business_reg_number,business_registered_date,mobile_number,staff_pf,alternative_number,email,address1,address2,address3,town,district,postal_code,province,alternate_address1,alternate_address2,alternate_address3,alternate_town,alternate_district,alternate_postal_code,preferred_language,customer_since,citizenship,profession,employer,dob,branch,relationship_manager,customer_status,customer_type,vip_flag,group_code)";
	    //  Yii::$app->db->createCommand($sql)->execute();

                }

             $modelData = $this->prepareUploadedResult($resultData);
			 //var_dump( $modelData );
			 return $this->render('daily_customer_update_form',['model' => $model, 'resultData' => $modelData]);
        } else {
            return $this->render('daily_customer_update_form', [
                'model' => $model,
            ]);
        }
	  
   }




}