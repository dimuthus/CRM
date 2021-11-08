<?php

namespace frontend\models\customer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\customer\Customer;
use yii\db\Query;

/**
 * CustomerSearch represents the model behind the search form about `frontend\models\customer\Customer`.
 */
class CustomerSearch extends Customer
{


    private $_caseID;
    private $_interactionID;
    public $card_number;
    public $account_number;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name','new_nic', 'cif', 'passport', 'mobile_number', 'alternative_number', 'dob', 'email','business_reg_number','account_number','card_number'], 'filter','filter'=>'trim'],
            [['mobile','contact_number'], 'match' ,'pattern'=>'/^\+[0-9]+$/u','message'=> 'e.g. +601661302200'],
            ['email', 'email', 'message'=>'Email address is invalid.'],
			//['full_name', 'required', 'when' => function($model) { return empty($model->full_name); }],
            //['mobile_number', 'required'],
            // [['InteractionID','wechat_id' ,'CaseID', 'customer_id', 'project_name', 'dealer_name', 'full_name', 'mobile_number', 'contact_number', 'email', 'fax', 'country_code', 'creation_datetime', 'last_modified_datetime'], 'safe'],
            [['errorField'], 'anyof', 'skipOnEmpty' => false, 'skipOnError' => false],

        ];
    }

    public function attributeLabels()
    {
        return [
           'full_name' => 'Full Name',
           'new_nic' => 'NRIC(new)',
           'cif' => 'Customer ID(CIF)',
           'passport' => 'Passport',
           'mobile_number' => 'Mobile',
           'dob' => 'Date of Birth',
		   'email' => 'Email',
           'business_reg_number'=>'Bus. Reg. Number'
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


     public function getFullname() {
        return $this->decryptString($this->full_name);
    }

     public function getMobilenumber() {
        return $this->decryptString($this->mobile_number);
    }




    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		$encryptionKey = Yii::$app->params['encryptionKey'];
        $query = new Query;
		$this->load($params);
		// compose the query
		$viewName="vi_search";
		if ($this->account_number!="" or  $this->card_number!="")
        { 
	//
			$productQuery = (new Query())->select('cif')->from('product')->andFilterWhere(['account_number'=>$this->encryptString($this->account_number)]);
	    /*
		$query->select('id,
		AES_DECRYPT(UNHEX(`full_name`),"'.$encryptionKey.'") AS `full_name`,
		`new_nic`,`passport`,`cif`, 
		AES_DECRYPT(UNHEX(`mobile_number`),"'.$encryptionKey.'") AS `mobile_number`,
     	-- AES_DECRYPT(UNHEX(`account_number`),"'.$encryptionKey.'") AS `account_number`')
	   ->from('vi_search_by_ac');
		    //$query->andFilterWhere([ '=','cif',0]);
			*/
		$query->select('id,
		AES_DECRYPT(UNHEX(`full_name`),"'.$encryptionKey.'") AS `full_name`,
		`new_nic`,`passport`,`cif`, 
		AES_DECRYPT(UNHEX(`mobile_number`),"'.$encryptionKey.'") AS `mobile_number`')
     	// AES_DECRYPT(UNHEX(`account_number`),"'.$encryptionKey.'") AS `account_number`')
		->from('customer')->andFilterWhere(['cif'=>$productQuery]);
	//		
		}
		else{
				   $query->select('id,
		AES_DECRYPT(UNHEX(`full_name`),"'.$encryptionKey.'") AS `full_name`,
		`new_nic`,`passport`,`cif`, 
		AES_DECRYPT(UNHEX(`mobile_number`),"'.$encryptionKey.'") AS `mobile_number`')
     	// AES_DECRYPT(UNHEX(`account_number`),"'.$encryptionKey.'") AS `account_number`')
		->from('customer');
		}
     
		$dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 15),
            'query' => $query,
        ]);
		if ($this->full_name=="" and $this->mobile_number=="" and  $this->new_nic=="" and $this->email=="" and $this->dob=="" 
		and $this->account_number=="" and $this->card_number=="" and $this->passport=="" and  $this->cif==""  )
        {   
		  $query->andFilterWhere([ '=','cif',0]);
		}
	   
	  /* if ($this->account_number!="" or  $this->card_number!="")
        { 
	           $query->andFilterWhere(['account_number'=>$this->encryptString($this->account_number)]);

	   } */
	    $query ->andFilterWhere(['full_name' =>$this->encryptString($this->full_name)])
            ->andFilterWhere(['mobile_number'=>$this->encryptString($this->mobile_number)])
            ->andFilterWhere(['new_nic'=> $this->new_nic])
			->andFilterWhere(['cif'=> $this->cif])
            ->andFilterWhere(['email'=>$this->encryptString(strtoupper($this->email))])
            ->andFilterWhere(['dob'=> $this->dob])

          //->andFilterWhere(['=', 'AES_DECRYPT(UNHEX(product.card_number),"'.$encryptionKey.'") ', $this->card_number])
	       ->andFilterWhere(['passport'=> $this->encryptString($this->passport)]);
         //  $query->limit(2);
         //echo $query->createCommand()->getRawSql();
         //die();
        return $dataProvider;
    }

 
    
    public function getCaseID(){
        return $this->_caseID;
    }
    
    public function setCaseID($_caseID){
        $this->_caseID = $_caseID;
    }
    
    public function getInteractionID(){
        return $this->_interactionID;
    }
    
    public function setInteractionID($_interactionID){
        $this->_interactionID = $_interactionID;
    }


    
 /**
     * Return the decrypted value of the field (does NOT assign
     * the decrypted value back to the attribute)
     * @return string
     */
  public function encryptString($stringValues) {
        if ($stringValues == '')
        return '';
       	$encryptionKey = Yii::$app->params['encryptionKey'];
		$query = new Query;
		$qtext='HEX(AES_ENCRYPT("'.$stringValues.'","'.$encryptionKey.'" ))';
		$list=$query->select(['HEX(AES_ENCRYPT("'.$stringValues.'","'.$encryptionKey.'" )) AS myEval'])->createCommand()->queryAll();
		$rows= $query->createCommand()->getRawSql();
		
		foreach($list as $item){
			$encFieldValue=$item['myEval'];

		}

		//die('xxx'.$encFieldValue.$qtext);
		return $encFieldValue;
       // $encVal=new \yii\db\Expression('HEX(AES_ENCRYPT("'.$stringValues.'","'.$encryptionKey.'" ))');
		
    }






    public function anyof($attribute_name, $params)
    {

        if (empty($this->new_nic)
            && empty($this->cif)
            && empty($this->full_name)
            && empty($this->mobile_number)
            && empty($this->alternative_number)
            && empty($this->email)
            && empty($this->passport)
        ) {
            $this->addError('errorField', Yii::t('user', 'At least 1 of the field must be filled up properly'));

            return false;
        }

        return true;
    }



     /**
     * Return the decrypted value of the field (does NOT assign
     * the decrypted value back to the attribute)
     * @return string
     */
   public function decryptString($stringValues) {
        // Nothing to decrypt
		$decryptFieldValue="";
		$encryptionKey = Yii::$app->params['encryptionKey'];
        if ($stringValues == '')
            return '';
        $key = "K-V-D-S";
		$query = new Query;
		$list=$query->select(['AES_DECRYPT(UNHEX("'.$stringValues.'"), "'.$encryptionKey.'") AS myDval'])->createCommand()->queryAll();
		//$rows= $query->createCommand()->getRawSql();
		//die('xxx');
		foreach($list as $item){
			$decryptFieldValue=$item['myDval'];

		}

		
		return $decryptFieldValue;
        //return Yii::$app->security->decryptByKey(utf8_decode($stringValues), $key);
        // return Yii::$app->securityManager->encrypt($stringValues,"AHSAN-AL-RUPOM");
    }
   
   protected function getCustomerCif($account_number){
   $connection = Yii::$app->getDb();
   $encryptionKey = Yii::$app->params['encryptionKey'];
   		$encryptedAC = $this->encryptString($account_number);

   $qry="SELECT  cif  FROM product WHERE product.account_number= '$encryptedAC' ";
   //echo $qry."</br>";
   $command = $connection->createCommand($qry);
   $result = $command->queryAll();
   if (count($result) > 0) {
           foreach ($result as $modelData) {
               return $modelData['cif'];
           }
			return true;
       } 
	   //return 0;
   }    
	 protected function getCustomerCifByCard($card_number){
	   $connection = Yii::$app->getDb();
	   $encryptionKey = Yii::$app->params['encryptionKey'];
	   $qry="SELECT  cif    FROM product WHERE AES_DECRYPT(UNHEX(product.card_number),'$encryptionKey') = '$card_number' ";
	   //echo $qry."</br>";
	   $command = $connection->createCommand($qry);
	   $result = $command->queryAll();
	   if (count($result) > 0) {
			   foreach ($result as $modelData) {
				   return $modelData['cif'];
			   }
				return true;
		   } 
		   return 0;
	   } 
}