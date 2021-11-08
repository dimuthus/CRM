<?php

namespace frontend\modules\tools\models\user;

use Yii;

/**
 * This is the model class for table "user_role".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 */
class UserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
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
            'name' => 'Value',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Status',
        ];
    }
}
