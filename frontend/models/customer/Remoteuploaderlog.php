<?php

namespace frontend\models\customer;

use Yii;

/**
 * This is the model class for table "remoteuploaderlog".
 *
 * @property integer $id
 * @property string $datetime
 * @property integer $uploaded_by
 * @property integer $no_of_records
 */
class Remoteuploaderlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'remoteuploaderlog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['uploaded_by', 'no_of_records'], 'integer']
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
            'uploaded_by' => 'Uploaded By',
            'no_of_records' => 'No Of Records',
        ];
    }
}
