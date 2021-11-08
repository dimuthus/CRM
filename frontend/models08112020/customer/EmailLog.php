<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "email_log".
 *
 * @property integer $id
 * @property string $datetime
 * @property integer $sent_by
 */
class EmailLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['sent_by'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datetime' => 'Datetime',
            'sent_by' => 'Sent By',
        ];
    }
}
