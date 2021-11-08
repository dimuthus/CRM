<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "race".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $last_upadted_by
 * @property string $created_datetime
 * @property string $last_updated_datetime
 * @property bool $deleted
 *
 * @property Customer[] $customers
 */
class Race extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'race';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'last_upadted_by'], 'integer'],
            [['created_datetime', 'last_updated_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 20],
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
            'last_upadted_by' => 'Last Upadted By',
            'created_datetime' => 'Created Datetime',
            'last_updated_datetime' => 'Last Updated Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['race' => 'id']);
    }
}
