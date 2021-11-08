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
class UserPasswordHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_password_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['created_by','user_id', 'deleted'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Status',
        ];
    }
}
