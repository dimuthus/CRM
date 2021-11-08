<?php

namespace frontend\models\complaint;

use Yii;

/**
 * This is the model class for table "complaint_user_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 */
class ComplaintUserType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'complaint_user_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'deleted'], 'integer'],
            [['creation_datetime', 'last_modified_datetime'], 'safe'],
            [['name'], 'string', 'max' => 250]
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
}
