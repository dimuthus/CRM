<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "source".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $created_by_datetime
 * @property bool $deleted
 *
 * @property Customer[] $customers
 * @property CustomerDataSource[] $customerDataSources
 */
class Source extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_by'], 'integer'],
            [['created_by_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 100],
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
            'created_by_datetime' => 'Created By Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['latest_data_source' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerDataSources()
    {
        return $this->hasMany(CustomerDataSource::className(), ['source_id' => 'id']);
    }
}
