<?php

namespace frontend\models\customer;

use Yii;
use frontend\modules\tools\models\user\User;
use yii\db\Query;
/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property integer $salutation_id
 * @property integer $gender
 * @property string $full_name
 * @property string $preferred_name
 * @property string $new_nic
 * @property string $old_nic
 * @property string $t_pin
 * @property string $marital_status
 * @property string $spouse_name
 * @property string $passport
 * @property string $driving_license
 * @property string $business_name
 * @property string $business_reg_number
 * @property string $business_registered_date
 * @property string $mobile_number
 * @property string $staff_pf
 * @property string $alternative_number
 * @property string $email
 * @property string $address1
 * @property string $address2
 * @property string $address3
 * @property string $town
 * @property string $district
 * @property string $postal_code
 * @property string $province
 * @property string $alternate_address1
 * @property string $alternate_address2
 * @property string $alternate_address3
 * @property string $alternate_town
 * @property string $alternate_district
 * @property string $alternate_postal_code
 * @property integer $preferred_language
 * @property string $customer_since
 * @property string $citizentship
 * @property string $proffesion
 * @property string $employer
 * @property string $dob
 * @property string $branch
 * @property string $relationship_manager
 * @property integer $customer_status
 * @property integer $customer_type
 * @property integer $created_by
 * @property string $created_datetime
 * @property integer $updated_by
 * @property string $last_updated_datetime
 * @property boolean $deleted
 * @property integer $cif
	* @property string $vip_flag
	* @property string $group_code
    * @property string $group_description

 *
 * @property Salutation $salutation
 * @property User $createdBy
 * @property User $updatedBy
 * @property CustomerStatus $customerStatus
 * @property CustomerType $customerType
 * @property Gender $gender0
 * @property integer $latest_campaign_id

 */
class Customer extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['preferred_language', 'created_by', 'updated_by', 'cif', 'latest_campaign_id'], 'integer'],
            [['business_registered_date', 'customer_since', 'dob', 'created_datetime', 'last_updated_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['full_name', 'spouse_name'], 'string', 'max' => 500],
			[[ 'created_by','vip_flag','full_name','mobile_number','preferred_language','cif'], 'required'],
            [['preferred_name'], 'string', 'max' => 200],
            [['new_nic', 'old_nic', 'driving_license'], 'string', 'max' => 16],
            [['t_pin', 'marital_status', 'town', 'district', 'postal_code','province', 'alternate_town', 'alternate_district', 'alternate_postal_code', 'cif','vip_flag','group_code','group_description'], 'string', 'max' => 50],
            [['passport', 'business_reg_number'], 'string', 'max' => 20],
            [['business_name', 'address1', 'address2', 'address3', 'citizenship', 'profession', 'branch'], 'string', 'max' => 100],
            [[ 'staff_pf', 'alternative_number'], 'string', 'max' => 150],
            [['email', 'employer', 'relationship_manager','mobile_number', 'customer_status', 'customer_type','salutation_id', 'gender'], 'string', 'max' => 250],
            [['alternate_address1', 'alternate_address2', 'alternate_address3'], 'string', 'max' => 150],
            //
			[[ 'cif'], 'unique', 'targetAttribute' => ['cif'], 'message' => 'Duplicate CIF'],
			[[ 'new_nic'], 'unique', 'targetAttribute' => ['new_nic'],'on'=>'update', 'message' => 'Duplicate nic'],
			[['new_nic'], 'unique', 'on'=>'update', 'when' => function($model){
    return $model->isAttributeChanged('new_nic');
}],
			[[ 'mobile_number'], 'validateMobileNumber','skipOnEmpty' => false, 'skipOnError' => false],

            //[['new_nic','passport'], 'validateNIC','skipOnEmpty' => false, 'skipOnError' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'salutation_id' => 'Salutation ID',
            'gender' => 'Gender',
            'full_name' => 'Full Name',
            'preferred_name' => 'Preferred Name',
            'new_nic' => 'New Nic',
            'old_nic' => 'Old Nic',
            't_pin' => 'T Pin',
            'marital_status' => 'Marital Status',
            'spouse_name' => 'Spouse Name',
            'passport' => 'Passport',
            'driving_license' => 'Driving License',
            'business_name' => 'Business Name',
            'business_reg_number' => 'Business Reg Number',
            'business_registered_date' => 'Business Registered Date',
            'mobile_number' => 'Mobile Number',
            'staff_pf' => 'Staff Pf',
            'alternative_number' => 'Alternative Number',
            'email' => 'Email',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'town' => 'Town',
            'district' => 'District',
            'postal_code' => 'Postal Code',
			'province'=> 'Province',
            'alternate_address1' => 'Alternate Address1',
            'alternate_address2' => 'Alternate Address2',
            'alternate_address3' => 'Alternate Address3',
            'alternate_town' => 'Alternate Town',
            'alternate_district' => 'Alternate District',
            'alternate_postal_code' => 'Alternate Postal Code',
            'preferred_language' => 'Preferred Language',
            'customer_since' => 'Customer Since',
            'citizenship' => 'Citizenship',
            'profession' => 'Profession',
            'employer' => 'Employer',
            'dob' => 'Dob',
            'branch' => 'Branch',
            'relationship_manager' => 'Relationship Manager',
            'customer_status' => 'Customer Status',
            'customer_type' => 'Customer Type',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'updated_by' => 'Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
            'deleted' => 'Deleted',
            'cif' => 'Customer Identification Number',
			'vip_flag'=>'VIP Flag',
			'group_code'=>'Group Code',
			'group_description'=>'Group Description',
        ];
    }


  public function validateNIC()
    {
        
        if (
                ( isset($this->new_nic) && trim($this->new_nic)=='')&&
                (
                    isset($this->passport)  && trim($this->passport)==''
                )
            )
            {
            
            //$this->addErrors(array('Please enter your mobile_number or alternate_contact_number.'));
                $this->addError('new_nic', 'NIC or Passport either one is mandatory');
                $this->addError('passport', 'NIC or Passport either one is mandatory');
            }        
    }


 public function validateMobileNumber()
    {
		$countOwn=0;
		$id=0;
	    $mobile_number = $this->encryptString($this->mobile_number);

		if(isset($this->id))
		$id=$this->id;
	    
		if($id==0){
			$count = Customer::find()->where(['mobile_number' => $mobile_number])->count();
		}
		else{
		    $countOwn = Customer::find()->where(['mobile_number' => $mobile_number,'id' => $this->id])->count();
		    if ($countOwn > 0)
				$count=0;
			else
				$count = Customer::find()->where(['mobile_number' => $mobile_number])->count();
			
		}
		
	
		//die('count='.$count);
				
        if ($count>0)
            {
            
                $this->addError('mobile_number', 'Duplicate Mobile');
            }        
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalutation() {
        return $this->hasOne(Salutation::className(), ['id' => 'salutation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerStatus() {
        return $this->hasOne(CustomerStatus::className(), ['id' => 'customer_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerType() {
        return $this->hasOne(CustomerType::className(), ['id' => 'customer_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender0() {
        return $this->hasOne(Gender::className(), ['id' => 'gender']);
    }

    public function getCreator() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getUpdator() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

     public function getFullname() {
        return $this->decryptString($this->full_name);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferredLanguage() {
        return $this->hasOne(CustomerLanguage::className(), ['id' => 'preferred_language']);
    }

    public function beforeSave($options = array()) {
       // $this->frequent_flyer_id = $this->encryptString($this->frequent_flyer_id);
       // $this->middle_name = $this->encryptString($this->middle_name);
       // $this->surname = $this->encryptString($this->surname);
       // $this->contact_number = $this->encryptString($this->contact_number);
       // $this->email2 = $this->encryptString($this->email2);
       // $this->customer_id = $this->encryptString($this->customer_id);
	   $this->email = $this->encryptString($this->email);
	   $this->mobile_number = $this->encryptString($this->mobile_number);
	   $this->full_name = $this->encryptString($this->full_name);
       $this->passport = $this->encryptString($this->passport);
	   $this->t_pin = $this->encryptString($this->t_pin);
       return true;
    }

    /**
     * Return the decrypted value of the field (does NOT assign
     * the decrypted value back to the attribute)
     * @return string
     */
    public function encryptStringOriginal($stringValues) {
        // Nothing to decrypt
        if ($stringValues == '')
            return '';
        $key = "K-V-D-S";
        return utf8_encode(Yii::$app->security->encryptByKey($stringValues, $key));
        // return Yii::$app->securityManager->encrypt($stringValues,"AHSAN-AL-RUPOM");
    }
  public function encryptString($stringValues) {
        // Nothing to decrypt
        if ($stringValues == '')
            return '';
       	$encryptionKey = Yii::$app->params['encryptionKey'];

        //return utf8_encode(Yii::$app->security->encryptByKey("HEX(AES_ENCRYPT($stringValues))", $key));
		return new \yii\db\Expression('HEX(AES_ENCRYPT("'.$stringValues.'","'.$encryptionKey.'" ))');
        // return Yii::$app->securityManager->encrypt($stringValues,"AHSAN-AL-RUPOM");
    }

    /**
     * Return the decrypted value of the field (does NOT assign
     * the decrypted value back to the attribute)
     * @return string
     */
    public function decryptStringOriginal($stringValues) {
        // Nothing to decrypt
        if ($stringValues == '')
            return '';
        $key = "K-V-D-S";
        return Yii::$app->security->decryptByKey(utf8_decode($stringValues), $key);
        // return Yii::$app->securityManager->encrypt($stringValues,"AHSAN-AL-RUPOM");
    }
 public function decryptString($stringValues) {
        // Nothing to decrypt
		$decryptFieldValue="";
		$encryptionKey = Yii::$app->params['encryptionKey'];
        if ($stringValues == '')
            return '';
        $key = "K-V-D-S";
		$query = new Query;
		$list=$query->select(['AES_DECRYPT(UNHEX("'.$stringValues.'"), "'.$encryptionKey.'") AS myDval'])->createCommand()->queryAll();
        // var_dump($list);
        // die();
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
