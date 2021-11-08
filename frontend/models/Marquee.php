<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "marquee".
 *
 * @property integer $id
 * @property string $message
 * @property integer $speed
 * @property integer $enabled
 */
class Marquee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'marquee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'speed', 'enabled'], 'integer'],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'speed' => 'Speed',
            'enabled' => 'Visibility',
        ];
    }
}
