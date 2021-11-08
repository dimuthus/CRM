<?php

namespace frontend\modules\survey\models;

use Yii;

/**
 * This is the model class for table "crm_survey_question_conditional_order".
 *
 * @property integer $id
 * @property integer $question_order_id
 * @property integer $response_question_id
 * @property integer $positive_response_question_order_id
 * @property integer $negative_response_question_order_id
 */
class CrmSurveyQuestionConditionalOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_survey_question_conditional_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_order_id', 'response_question_id', 'positive_response_question_order_id', 'negative_response_question_order_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_order_id' => 'Question Order ID',
            'response_question_id' => 'Response Question ID',
            'positive_response_question_order_id' => 'Positive Response Question Order ID',
            'negative_response_question_order_id' => 'Negative Response Question Order ID',
        ];
    }
}
