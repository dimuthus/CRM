<?php

namespace frontend\models\cases;


use Yii;

/**
 * This is the model class for table "barnd".
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
* @property CustomerSmokingPreferencesBrand[] $customerBrandPreferences
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_by'], 'integer'],
            [['created_datetime'], 'safe'],
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
            'created_datetime' => 'Created Datetime',
            'deleted' => 'Deleted'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases()
    {
        return $this->hasMany(CustomerCases::className(), ['brand' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerSmokingPreferencesBrand()
    {
        return $this->hasMany(CustomerSmokingPreferencesBrand::className(), ['brand_id' => 'id']);
    }
}
