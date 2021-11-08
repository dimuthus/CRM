<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "customer_smoking_details".
 *
 * @property int $id
 * @property bool $smoker
 * @property int $brand1
 * @property string $created_by_datetime
 * @property int $product1
 * @property int $created_by
 * @property int $customer_id
 */
class CustomerSmokingDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_smoking_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['smoker'], 'boolean'],
            [['brand1', 'customer_id'], 'required'],
            [['brand1', 'product1', 'created_by', 'customer_id'], 'integer'],
            [['created_by_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'smoker' => 'Smoker',
            'brand1' => 'Brand1',
            'created_by_datetime' => 'Created By Datetime',
            'product1' => 'Product1',
            'created_by' => 'Created By',
            'customer_id' => 'Customer ID',
        ];
    }
}
