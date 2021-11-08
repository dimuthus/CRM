<?php

namespace frontend\models\customer;

use Yii;
use frontend\modules\tools\models\user\User;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $region_name
 * @property int $created_by
 * @property string $created_datetime
 * @property int $last_updated_by
 * @property string $last_updated_datetime
 *
 * @property User $createdBy
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'last_updated_by', 'deleted'], 'integer'],
            [['created_datetime', 'last_updated_datetime'], 'safe'],
            [['region_name'], 'string', 'max' => 100],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_name' => 'Region Name',
            'created_by' => 'Created By',
            'created_datetime' => 'Created Datetime',
            'last_updated_by' => 'Last Updated By',
            'last_updated_datetime' => 'Last Updated Datetime',
			'deleted' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
