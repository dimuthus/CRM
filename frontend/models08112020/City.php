<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "state".
 *
 * @property string $code
 * @property string $name
 * @property string $full_name
 * @property string $iso3
 * @property integer $number
 * @property string $continent_code
 * @property string $timezone
 * @property integer $enabled
 * @property integer $created_by
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property integer $deleted
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
     public function rules()
    {
        return [
            [['created_by', 'deleted'], 'integer'],
            //[['creation_datetime', 'last_modified_datetime'], 'required'],
            [['creation_datetime', 'last_modified_datetime','state_id'], 'safe'],
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
            'name' => 'Value',
            'created_by' => 'Created By',
            'creation_datetime' => 'Creation Datetime',
            'last_modified_datetime' => 'Last Modified Datetime',
            'deleted' => 'Status',
        ];
    }
}
