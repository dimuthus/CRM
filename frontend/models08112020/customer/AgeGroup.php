<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "age_group".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property bool $deleted
 * @property string $created_datetime
 *
 * @property Customer[] $customers
 */
class AgeGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'age_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_datetime'], 'safe'],
            [['name'], 'string', 'max' => 10],
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
            'deleted' => 'Deleted',
            'created_datetime' => 'Created Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['age_group' => 'id']);
    }
}
