<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_details".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $salutation
 * @property string $first_name
 * @property string $last_name
 * @property string $nric
 * @property string $passport
 * @property string $dob
 * @property string $gender
 * @property integer $age
 * @property string $age_group
 * @property string $race
 * @property string $nationality
 * @property string $phone
 * @property string $mobile
 * @property string $preferred_language
 * @property string $address1
 * @property string $address2
 * @property integer $postcode
 * @property string $city
 * @property string $other_city
 * @property string $state
 * @property string $email
 * @property string $consent
 * @property string $smoker
 * @property string $latest_brand
 * @property string $latest_product
 * @property string $created_by
 * @property string $created_datetime
 */
class CustomerDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'age', 'postcode'], 'integer'],
            [['dob', 'created_datetime'], 'safe'],
            [['mobile'], 'required'],
            [['customer_id'], 'string', 'max' => 11],
            [['salutation', 'gender'], 'string', 'max' => 8],
            [['first_name', 'last_name', 'address1', 'address2', 'latest_brand'], 'string', 'max' => 100],
            [['nric'], 'string', 'max' => 16],
            [['passport', 'race', 'preferred_language', 'other_city'], 'string', 'max' => 20],
            [['age_group'], 'string', 'max' => 10],
            [['nationality', 'created_by'], 'string', 'max' => 255],
            [['phone', 'mobile'], 'string', 'max' => 15],
            [['city'], 'string', 'max' => 250],
            [['state'], 'string', 'max' => 30],
            [['email', 'latest_product'], 'string', 'max' => 150],
            [['consent', 'smoker'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'salutation' => 'Salutation',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'nric' => 'Nric',
            'passport' => 'Passport',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'age' => 'Age',
            'age_group' => 'Age Group',
            'race' => 'Race',
            'nationality' => 'Nationality',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'preferred_language' => 'Preferred Language',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'postcode' => 'Postcode',
            'city' => 'City',
            'other_city' => 'Other City',
            'state' => 'State',
            'email' => 'Email',
            'consent' => 'Consent',
            'smoker' => 'Smoker',
            'latest_brand' => 'Latest Brand',
            'latest_product' => 'Latest Product',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
        ];
    }
}
