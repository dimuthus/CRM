<?php

namespace frontend\modules\survey\models;

use Yii;

/**
 * This is the model class for table "crm_survey".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $updated
 * @property string $opening_date
 * @property string $closing_date
 * @property integer $group_id
 */
class CrmSurvey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_survey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['updated', 'opening_date', 'closing_date'], 'safe'],
            [['group_id'], 'integer'],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Survey Name',
            'description' => 'Description',
            'updated' => 'Updated',
            'opening_date' => 'Opening Date',
            'closing_date' => 'Closing Date',
            'group_id' => 'Group ID',
        ];
    }
    
}
