<?php

namespace frontend\models\report;

use Yii;

/**
 * This is the model class for table "csat_survey".
 *
 * @property integer $id
 * @property integer $survey_response_id
 * @property string $text
 * @property string $first_name
 * @property string $answer
 * @property string $created_datetime
 * @property string $name
 */
class CsatSurvey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csat_survey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','survey_response_id',], 'integer'],
            [['created_datetime'], 'safe'],
            
            [['text','answer','first_name','name'], 'string', 'max' => 10000],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_response_id' => 'Survey ID',
            'text' => 'question',
            'first_name' => 'Customer Name',
            'created_datetime' => 'Date Created',
            'answer' => 'answer',
            'name' => 'survey type',
        ];
    }
}
