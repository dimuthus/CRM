<?php

namespace frontend\models\product;

use Yii;
use frontend\models\customer\Customer;
use frontend\modules\tools\models\user\User;
use yii\db\Query;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $account_number
 * @property string $card_number
 * @property string $product_name
 * @property string $account_limit
 * @property string $account_status
 * @property string $branch_name
 * @property string $digital_products
 * @property string $product_field1
 * @property string $product_field2
 * @property integer $created_by
 * @property string $created_by_datetime
 * @property integer $last_updated_by
 * @property string $last_upated_datetime
 * @property integer $deleted
 * @property integer $customer_id
 *
 * @property CustomerSmokingPreferences[] $customerSmokingPreferences
 * @property Customer $customer
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_number', 'account_limit'], 'required'],
            [['created_by', 'last_updated_by', 'deleted', 'customer_id','cif'], 'integer'],
            [['created_by_datetime', 'last_upated_datetime'], 'safe'],
            [['product_name', 'branch_name', 'digital_products', 'product_field1', 'product_field2'], 'string', 'max' => 200],
            [['account_status','account_number','card_number','account_limit','relationship','nic'], 'string', 'max' => 50],
			[['account_number','account_limit','account_status','branch_name'], 'required'],
            /*this make double reords, pls comment of add enablevidation=fasle to the form*/
            //[['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_number' => 'Account Number',
            'card_number' => 'Card Number',
            'product_name' => 'Product Name',
            'account_limit' => 'Account Limit',
            'account_status' => 'Account Status',
            'branch_name' => 'Branch Name',
            'digital_products' => 'Digital Products',
            'product_field1' => 'Product Field1',
            'product_field2' => 'Product Field2',
            'created_by' => 'Created By',
            'created_by_datetime' => 'Created By Datetime',
            'last_updated_by' => 'Last Updated By',
            'last_upated_datetime' => 'Last Upated Datetime',
            'deleted' => 'Deleted',
            'customer_id' => 'Customer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerSmokingPreferences()
    {
        return $this->hasMany(CustomerSmokingPreferences::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
      public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
	
	
	  public function beforeSave($options = array()) {
       
	   $this->account_number = $this->encryptString($this->account_number);
	   $this->card_number = $this->encryptString($this->card_number);
	  
       return true;
    }
	
	
	
	 public function encryptString($stringValues) {
        if ($stringValues == '')
            return '';
       	   $encryptionKey = Yii::$app->params['encryptionKey'];
			return new \yii\db\Expression('HEX(AES_ENCRYPT("'.$stringValues.'","'.$encryptionKey.'" ))');
    }
	
	 public function decryptString($stringValues) {
        // Nothing to decrypt
		$decryptFieldValue="";
		$encryptionKey = Yii::$app->params['encryptionKey'];
        if ($stringValues == '')
            return '';
		$query = new Query;
		$list=$query->select(['AES_DECRYPT(UNHEX("'.$stringValues.'"), "'.$encryptionKey.'") AS myDval'])->createCommand()->queryAll();
		
		foreach($list as $item){
			$decryptFieldValue=$item['myDval'];

		}

		
		return $decryptFieldValue;
       
    }
	
}
