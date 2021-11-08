<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "case_status".
 *
 * @property int $id
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property int $deleted
 *
 * @property CustomerCases[] $customerCases
 * @property CustomerCases[] $customerCases0
 */
class CaseStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'case_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted','updated_by'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['name'], 'string', 'max' => 10],
            [['name'], 'required', 'message'=>'']
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
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases()
    {
        return $this->hasMany(CustomerCases::className(), ['case_status' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerCases0()
    {
        return $this->hasMany(CustomerCases::className(), ['first_call_resolution' => 'id']);
    }
}
