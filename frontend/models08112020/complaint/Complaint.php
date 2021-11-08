<?php

namespace frontend\models\complaint;
use frontend\modules\tools\models\user\User;
use frontend\models\customer\Customer;
use frontend\models\cases;

use Yii;

/**
 * This is the model class for table "complaint".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $customer_id
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $subbrand_id
 * @property integer $product_id
 * @property integer $packsize_id
 * @property integer $color_id
 * @property string $batch_no
 * @property integer $user_type_id
 * @property string $purchase_date
 * @property string $description
 * @property integer $proof_of_purchase_id
 */
class Complaint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complaint';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'customer_id', 'created_by', 'subbrand_id', 'product_id', 'packsize_id', 'color_id', 'user_type_id', 'proof_of_purchase_id'], 'integer'],
            [['customer_id', 'created_by','brand_id'], 'required'],
            [['creation_datetime', 'last_modified_datetime', 'purchase_date'], 'safe'],
            [['description','place_of_purchase','case_id'], 'string'],
            [['batch_no'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand',
            'customer_id' => 'Customer',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'subbrand_id' => 'Sub Brand',
            'product_id' => 'Product',
            'packsize_id' => 'Pack size',
            'color_id' => 'Color',
            'batch_no' => 'Batch No',
            'user_type_id' => 'User Type',
            'purchase_date' => 'Purchase Date',
            'description' => 'Description',
            'proof_of_purchase_id' => 'Proof Of Purchase',
            'place_of_purchase'=>'Place Of Purchase',
            'brand.name' => 'Brand',
            'subbrand.name' => 'Subbrand',
            'product.name' => 'Product',
            'creator.username' => 'Created by',
            'packsize.name' => 'Packsize',
            'color.name' => 'Color',
            'usertype.name' => 'User Type',
            'proofofpurchase.name' => 'Exchange Completed',
            'displaycustomerid.customer_id'=>'Customer ',
            'case_id'=>'Case ID',
        ];
    }
    
     public function anyof($attribute_name, $params)
    {

        if (
            empty($this->brand_id) && 
            empty($this->subbrand_id) && 
            empty($this->product_id) && 
            empty($this->packsize_id) && 
            empty($this->color_id) && 
            empty($this->batch_no) && 
            empty($this->user_type_id)  && 
            empty($this->purchase_date)  && 
            empty($this->description)  && 
            empty($this->proof_of_purchase_id)  && 
            empty($this->place_of_purchase) 
        ) {
            $this->addError('errorField', Yii::t('user', 'At least 1 of the field must be filled up properly'));

            return false;
        }

        return true;
    }
       /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(ComplaintBrand::className(), ['id' => 'brand_id']);
    }
    
        public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'customer_id']);
    }
    public function getSubbrand()
    {
        return $this->hasOne(ComplaintSubBrand::className(), ['id' => 'subbrand_id']);
    }
    
      public function getProduct()
    {
        return $this->hasOne(ComplaintProduct::className(), ['id' => 'product_id']);
    }
    
      public function getPacksize()
    {
        return $this->hasOne(ComplaintPacksize::className(), ['id' => 'product_id']);
    }
    
        public function getColor()
    {
        return $this->hasOne(ComplaintColor::className(), ['id' => 'product_id']);
    }
    
    
           public function getUsertype()
    {
        return $this->hasOne(ComplaintUserType::className(), ['id' => 'user_type_id']);
    }
             public function getProofofpurchase()
    {
        return $this->hasOne(ComplaintProofOfPurchase::className(), ['id' => 'proof_of_purchase_id']);
    }
    
               public function getDisplaycustomerid()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
}
