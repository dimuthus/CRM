<?php

namespace frontend\modules\survey\models;
use Yii;
use frontend\models\campaign\Campaign;

/**
 * This is the model class for table "crm_survey_question_order".
 *
 * @property integer $id
 * @property integer $question_id
 * @property integer $servey_id
 * @property integer $order
 * @property integer $is_conditional
 * @property integer $conditional_order_id
 */
class CrmSurveyQuestionOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_survey_question_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'servey_id', 'order', 'conditional_order_id','is_conditional'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Question Text',
            'servey_id' => 'Servey Name',
            'order' => 'Display Sequence',
            'conditional_order_id' => 'Conditional Question(Next to Display)',
            'is_conditional'=>'is_conditional',
            'servey.name' => 'Servey Name',
        ];
    }

    public function getQuestion()
    {
        return $this->hasOne(CrmSurveyQuestion::className(), ['id' => 'question_id']);
    }

     public function getSurveyOriginal()
    {
        return $this->hasOne(CrmSurvey::className(), ['id' => 'servey_id']);
    }
	
	 public function getSurvey()
    {
        return $this->hasOne(Campaign::className(), ['id' => 'servey_id']);
    }
}
