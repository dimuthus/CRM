<?php

namespace frontend\models\cases;


use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $created_by_datetime
 * @property int $last_updated_by
 * @property string $last_upated_datetime
 * @property bool $deleted
 *
 * @property CustomerCases[] $customerCases
 * @property CustomerSmokingPreferences[] $customerSmokingPreferences
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
            [['name'], 'required'],
            [['created_by', 'last_updated_by'], 'integer'],
            [['created_by_datetime', 'last_upated_datetime'], 'safe'],
            [['deleted'], 'boolean'],
            [['name'], 'string', 'max' => 150],
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
            'last_updated_by' => 'Last Updated By',
            'last_upated_datetime' => 'Last Upated Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases()
    {
        return $this->hasMany(CustomerCases::className(), ['product' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerSmokingPreferences()
    {
        return $this->hasMany(CustomerSmokingPreferences::className(), ['product_id' => 'id']);
    }
}
