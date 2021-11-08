<?php

namespace frontend\modules\survey\models;

use Yii;

/**
 * This is the model class for table "crm_survey_response_choice".
 *
 * @property integer $id
 * @property integer $question_id
 * @property string $text
 * @property integer $is_deleted
 * @property integer $question_type_id

 */
class CrmSurveyResponseChoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_survey_response_choice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id'], 'integer'],
            [['text'], 'string'],
            [['is_deleted'], 'integer'],
            [['question_type_id'], 'integer']


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_id' => 'Select question',
            'text' => 'Survey question-option text',
            'is_deleted' => 'Activation',
           ] ;
    }
}
