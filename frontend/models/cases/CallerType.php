<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "caller_type".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property string $created_datetime
 * @property bool $deleted
 *
 * @property CustomerCases[] $customerCases
 */
class CallerType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'caller_type';
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
            'created_datetime' => 'Created Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases()
    {
        return $this->hasMany(CustomerCases::className(), ['caller_type' => 'id']);
    }
}
