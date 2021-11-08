<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_smoking_behaviour".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $smoker
 * @property string $product
 * @property string $brand
 * @property string $created_by
 * @property string $created_by_datetime
 */
class CustomerSmokingBehaviour extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_smoking_behaviour';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_by_datetime'], 'safe'],
            [['customer_id'], 'string', 'max' => 11],
            [['smoker'], 'string', 'max' => 3],
            [['product'], 'string', 'max' => 150],
            [['brand'], 'string', 'max' => 100],
            [['created_by'], 'string', 'max' => 255]
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
            'smoker' => 'Smoker',
            'product' => 'Product',
            'brand' => 'Brand',
            'created_by' => 'Created By',
            'created_by_datetime' => 'Created By Datetime',
        ];
    }
}
