<?php

namespace frontend\models\request;

use Yii;

/**
 * This is the model class for table "service_request_device".
 *
 * @property integer $id
 * @property integer $type
 * @property string $model
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 */
class RequestDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_request_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_by', 'deleted'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['model'], 'string', 'max' => 250],
            [['model'], 'required', 'message'=>'']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'model' => 'Model',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Status',
        ];
    }

    public function getName() {
        return $this->typeObject->name." - ".$this->model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeObject()
    {
        return $this->hasOne(RequestDeviceType::className(), ['id' => 'type']);
    }
}
