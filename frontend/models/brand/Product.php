<?php

namespace frontend\models\brand;

use frontend\models\Country;
use frontend\models\City;
use frontend\models\State;
use frontend\models\customer\Region;
use frontend\modules\tools\models\user\User;
use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $serial_number
 * @property integer $customer_id
 * @property integer $product_category_id
 * @property integer $product_type_id
 * @property string $product_model
 * @property string $pop_date
 * @property string $warranty_start_date
 * @property string $warranty_expiry_date
 * @property string $warranty_code
 * @property string $warranty_description
 * @property string $shipping_date
 * @property string $shipping_country
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 */
class Product extends \yii\db\ActiveRecord
{

    public $errorField;

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
            [['customer_id', 'address_line_1', 'address_line_2', 'country'], 'required'],
            ['serial_number', 'match' ,'pattern'=>'/^[0-9]+$/u','message'=> 'Serial number must be a numeric value.'],
            ['product_model', 'match' ,'pattern'=>'/^[A-Za-z0-9]+$/u','message'=> 'Product model must be an alphanumeric value.'],
            ['warranty_code', 'match' ,'pattern'=>'/^[A-Za-z0-9]+$/u','message'=> 'Warranty code must be an alphanumeric value.'],
            ['machine_number', 'match' ,'pattern'=>'/^[A-Za-z0-9]+$/u','message'=> 'Machine number must be an alphanumeric value.'],
            [['customer_id', 'product_category_id', 'product_type_id', 'created_by','shipping_country', 'city', 'state', 'country', 'region'], 'integer'],
            [['pop_date', 'warranty_start_date', 'warranty_expiry_date', 'shipping_date', 'creation_datetime', 'last_modified_datetime'], 'safe'],
            [['warranty_description'], 'string'],
            [['serial_number', 'product_model', 'warranty_code'], 'string', 'max' => 100],
            [['address_line_1', 'address_line_2', 'location_tip'], 'string', 'max' => 250],
            [['serial_number', 'product_model', 'warranty_code','warranty_description', 'machine_number'],'filter','filter'=>'trim'],
            [['errorField'], 'anyof', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'customer_id' => 'Customer ID',
            'product_category_id' => 'Product Category',
            'product_type_id' => 'Product Type',
            'product_model' => 'Product Model',
			'machine_number'=>'Machine Number',
            'pop_date' => 'Batch No',
            'warranty_start_date' => 'Warranty start date',
            'warranty_expiry_date' => 'Warranty expiry date',
            'warranty_code' => 'Warranty Code',
            'warranty_description' => 'Warranty Description',
            'shipping_country' => 'Proof Of Purchase',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Date & Time',
            'last_modified_datetime' => 'Last Update Date & Time',

            'category.name' => 'Product Category',
            'type.name' => 'Product Type',
            'creator.username' => 'Created by',
			'address_line_1' => 'Address Line 1',
			'address_line_2' => 'Address Line 2',
			'location_tip' => 'Location Tip',
			'city' => 'City',
			'cityid.name' => 'City',
			'state' => 'State',
			'stateid.name' => 'State',
			'country' => 'Country',
			'countryid.name' => 'Country',
			'region' => 'Region',
			'regionid.region_name' => 'Region',
        ];
    }

      /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'product_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryid()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

	public function getStateid()
    {
        return $this->hasOne(State::className(), ['id' => 'state']);
    }

	public function getCityid()
    {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }
	public function getRegionid()
    {
        return $this->hasOne(Region::className(), ['id' => 'region']);
    }
    public function anyof($attribute_name, $params)
    {

        if (
            empty($this->serial_number) &&
            empty($this->product_category_id) &&
            empty($this->product_type_id) &&
            empty($this->product_model) &&
			empty($this->machine_number) &&
            empty($this->pop_date) &&
            empty($this->warranty_start_date) &&
            empty($this->warranty_expiry_date)  &&
            empty($this->warranty_code)  &&
            empty($this->warranty_description)  &&
            empty($this->shipping_date)  &&
            empty($this->shipping_country)
        ) {
            $this->addError('errorField', Yii::t('user', 'At least 1 of the field must be filled up properly'));

            return false;
        }

        return true;
    }

    public function beforeSave() {
        if(empty($this->shipping_country))
            $this->shipping_country = null;

        return true;
    }
}
