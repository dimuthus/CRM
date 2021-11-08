<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "customer_status".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property string $created_datetime
 * @property boolean $deleted
 *
 * @property Customer[] $customers
 */
class CustomerStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_by'], 'required'],
            [['created_by'], 'integer'],
            [['created_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['customer_status' => 'id']);
    }
}
