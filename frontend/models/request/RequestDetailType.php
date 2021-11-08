<?php

namespace frontend\models\request;

use Yii;

/**
 * This is the model class for table "service_request_detail_type".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 */
class RequestDetailType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_request_detail_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted'], 'integer'],
            [['creation_datetime', 'last_modified_datetime','type_id'], 'safe'],
            [['name'], 'string', 'max' => 250],
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
            'type_id' => 'Service Request Type',
            'name' => 'Name',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Status',
        ];
    }
}
