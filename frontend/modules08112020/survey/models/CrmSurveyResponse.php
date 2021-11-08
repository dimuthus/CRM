<?php
//// gooner/
namespace frontend\modules\survey\models;


use Yii;
use frontend\models\campaign\Campaign;
use frontend\models\cases\CustomerCases;

/**
 * This is the model class for table "crm_survey_response".
 *
 * @property integer $id
 * @property integer $survey_id
 * @property integer $respondent_id
 * @property string $updated_date
 * @property string $started_at
 * @property string $completed_at
 * @property integer $case_id
   
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
            [['survey_id', 'respondent_id','case_id'], 'integer'],
            [['updated_date', 'started_at', 'completed_at'], 'safe'],
            //[['survey_id'], 'unique']
            ['survey_id','unique', 'targetAttribute' => ['survey_id', 'respondent_id','case_id']] //jalis added this rules.

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
            'surveyName.name'=>'Campaign Name',
            'case_id'=>'case_id',
        ];
    }

       public function getSurveyName()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'survey_id']);
    }

           public function getcustomer_cases()
    {
        return $this->hasOne(CustomerCases::className(), ['id' => 'case_id']);
    }



}
