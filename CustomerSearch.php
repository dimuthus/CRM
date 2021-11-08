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
        // compose the query
        $query->select('customer.id,
							AES_DECRYPT(UNHEX(`customer`.`full_name`),"'.$encryptionKey.'") AS `full_name`,
                            ,`customer`.`new_nic`
                            , `customer`.`passport`
                            , `customer`.`cif`
							,AES_DECRYPT(UNHEX(`customer`.`mobile_number`),"'.$encryptionKey.'") AS `mobile_number`,

                            , `customer`.`alternative_number`
                            , `customer`.`dob`
                            , `customer`.`email`
                            , `customer`.`business_reg_number`
                            ,`product`.`account_number`')
            ->from('customer')
            ->join( 'LEFT OUTER JOIN', 
                'product',
                'product.customer_id = customer.id')
                ->groupby('customer.cif')->limit(0,10);


        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'pagination' => array('pageSize' => 15),
            'query' => $query,
        ]);


        if ($this->full_name=="" and $this->mobile_number=="" and  $this->new_nic=="" and $this->email=="" and $this->dob=="" 
		and $this->account_number=="" and $this->card_number=="" and $this->passport==""  )
        {   
		    $d1=date('Y-m-d h:i:s');
			$d2=date('Y-m-d h:i:s', strtotime("-1 Weeks"));

			$query->andFilterWhere([ 'between','customer.created_datetime',$d2,$d1]);
		}
	
	    $query->andFilterWhere([
           // 'product.serial_number'=> $this->SerialNumber,
          //  'customer_cases.case_id'=> $this->CaseID,
          //  'inbound_interaction.interaction_id'=> $this->InteractionID,
            'customer.cif'=> $this->cif
        ]);
		$encryptionKey = Yii::$app->params['encryptionKey'];

        $query ->andFilterWhere(['like', 'AES_DECRYPT(UNHEX(customer.`full_name`),"'.$encryptionKey.'") ', $this->full_name])
            ->andFilterWhere(['like','AES_DECRYPT(UNHEX(customer.`mobile_number`),"'.$encryptionKey.'") ', $this->mobile_number])
            ->andFilterWhere(['new_nic'=> $this->new_nic])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere([ 'dob'=> $this->dob])
            ->andFilterWhere([ 'product.account_number'=> $this->account_number])
            ->andFilterWhere([ 'product.card_number'=> $this->card_number])
	       ->andFilterWhere(['like', 'AES_DECRYPT(UNHEX(customer.`passport`),"'.$encryptionKey.'")', $this->passport]);
$query->limit(2);
            //echo $query->createCommand()->getRawSql();
                       
         //die('as');
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
        // Nothing to decrypt
        if ($stringValues == '')
            return '';
        $key = "K-V-D-S";
        return utf8_encode(Yii::$app->security->encryptByKey($stringValues, $key));
        // return Yii::$app->securityManager->encrypt($stringValues,"AHSAN-AL-RUPOM");
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
    

}
