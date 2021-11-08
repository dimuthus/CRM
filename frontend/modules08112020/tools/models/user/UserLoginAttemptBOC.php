<?php

namespace frontend\modules\tools\models\user;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
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
class UserLoginAttempt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_login_attempt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            [['deleted'], 'integer'],
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
            'username' => 'User Name',
			'ip_address' => 'User IP',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Status'
        ];
    }
    
    
}
