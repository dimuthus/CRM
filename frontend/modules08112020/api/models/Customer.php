<?php

namespace frontend\modules\api\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\models\User;
use frontend\modules\api\models\query\CustomerQuery;
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
 * @property string $cif
 *
 * @property Salutation $salutation
 * @property User $createdBy
 * @property User $updatedBy
 * @property CustomerStatus $customerStatus
 * @property CustomerType $customerType
 * @property Gender $gender0
 * @property Product[] $products
 */
class Customer extends \yii\db\ActiveRecord
{
	public $VIP;
	public $LANG;
	public $REGISTRATION;
	public $REGISTERED;
	public $VALIDATE;		
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['salutation_id', 'gender', 'preferred_language', 'customer_status', 'customer_type', 'created_by', 'updated_by'], 'integer'],
            [['business_registered_date', 'customer_since', 'dob', 'created_datetime', 'last_updated_datetime'], 'safe'],
            //[['email', 'created_by'], 'required'],
            [['deleted'], 'boolean'],
            [['full_name', 'spouse_name'], 'string', 'max' => 500],
            [['preferred_name'], 'string', 'max' => 200],
            [['new_nic', 'old_nic', 'driving_license'], 'string', 'max' => 16],
            [['t_pin', 'marital_status', 'town', 'district', 'postal_code', 'alternate_town', 'alternate_district', 'alternate_postal_code'], 'string', 'max' => 50],
            [['passport', 'business_reg_number', 'cif'], 'string', 'max' => 20],
            [['business_name', 'address1', 'address2', 'address3', 'citizentship', 'proffesion', 'branch'], 'string', 'max' => 100],
            [['mobile_number', 'staff_pf', 'alternative_number'], 'string', 'max' => 15],
            [['email', 'employer', 'relationship_manager'], 'string', 'max' => 250],
            [['alternate_address1', 'alternate_address2', 'alternate_address3'], 'string', 'max' => 150],
            [['new_nic'], 'unique'],
            [['old_nic'], 'unique'],
			 [['mobile_number'], 'required'],
            //[['salutation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Salutation::className(), 'targetAttribute' => ['salutation_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            //[['customer_status'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerStatus::className(), 'targetAttribute' => ['customer_status' => 'id']],
            //[['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerType::className(), 'targetAttribute' => ['customer_type' => 'id']],
            //[['gender'], 'exist', 'skipOnError' => true, 'targetClass' => Gender::className(), 'targetAttribute' => ['gender' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
            'alternate_address1' => 'Alternate Address1',
            'alternate_address2' => 'Alternate Address2',
            'alternate_address3' => 'Alternate Address3',
            'alternate_town' => 'Alternate Town',
            'alternate_district' => 'Alternate District',
            'alternate_postal_code' => 'Alternate Postal Code',
            'preferred_language' => 'Preferred Language',
            'customer_since' => 'Customer Since',
            'citizentship' => 'Citizentship',
            'proffesion' => 'Proffesion',
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
            'cif' => 'Cif',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalutation()
    {
        return $this->hasOne(Salutation::className(), ['id' => 'salutation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerStatus()
    {
        return $this->hasOne(CustomerStatus::className(), ['id' => 'customer_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerType()
    {
        return $this->hasOne(CustomerType::className(), ['id' => 'customer_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender0()
    {
        return $this->hasOne(Gender::className(), ['id' => 'gender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['customer_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerQuery(get_called_class());
    }
}
