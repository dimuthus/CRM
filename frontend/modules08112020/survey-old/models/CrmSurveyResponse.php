<?php

namespace frontend\modules\survey\models;

use Yii;

/**
 * This is the model class for table "crm_survey_response".
 *
 * @property integer $id
 * @property integer $survey_id
 * @property integer $respondent_id
 * @property string $updated_date
 * @property string $started_at
 * @property string $completed_at
 */
class CrmSurveyResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_survey_response';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['survey_id', 'respondent_id'], 'required'],
            [['survey_id', 'respondent_id'], 'integer'],
            [['updated_date', 'started_at', 'completed_at'], 'safe'],
            [['survey_id'], 'unique']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'survey_id' => 'Survey ID',
            'respondent_id' => 'Respondent ID',
            'updated_date' => 'Updated Date',
            'started_at' => 'Started At',
            'completed_at' => 'Completed At',
            'surveyName.name'=>'Survey',
        ];
    }
    
       public function getSurveyName()
    {
        return $this->hasOne(CrmSurvey::className(), ['id' => 'survey_id']);
    }
}
