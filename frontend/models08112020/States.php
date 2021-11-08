<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "country".
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
class States extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number'], 'integer'],
            //[['creation_datetime', 'last_modified_datetime'], 'required'],
           // [['creation_datetime', 'last_modified_datetime'], 'safe'],
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
           // 'code' => 'Code',
            'name' => 'state',
          //  'full_name' => 'Full Name',
          //  'iso3' => 'Iso3',
          //  'number' => 'Number',
          //  'continent_code' => 'Continent Code',
           // 'timezone' => 'Timezone',
           // 'enabled' => 'Enabled',
           // 'created_by' => 'Created By',
          //  'creation_datetime' => 'Creation Datetime',
          //  'last_modified_datetime' => 'Last Modified Datetime',
          //  'deleted' => 'Status',
        ];
    }
}
