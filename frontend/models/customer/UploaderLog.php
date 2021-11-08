<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "uploader_log".
 *
 * @property integer $id
 * @property string $filename
 * @property integer $uploaded_by
 * @property string $uploaded_date
 */
class UploaderLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uploader_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uploaded_by'], 'integer'],
            [['uploaded_date'], 'safe'],
            [['filename'], 'string', 'max' => 400]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'uploaded_by' => 'Uploaded By',
            'uploaded_date' => 'Uploaded Date',
        ];
    }
}
