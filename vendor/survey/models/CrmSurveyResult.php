<?php

namespace frontend\modules\survey\models;

use Yii;

/**
 * This is the model class for table "crm_survey_result".
 *
 * @property integer $id
 * @property integer $survey_response_id
 * @property integer $respondent_id
 * @property integer $question_id
 * @property string $answer
 */
class CrmSurveyResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_survey_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['survey_response_id', 'respondent_id', 'question_id'], 'integer'],
            [['answer'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_response_id' => 'Survey Response ID',
            'respondent_id' => 'Respondent ID',
            'question_id' => 'Question ID',
            'answer' => 'Answer',
        ];
    }
}
