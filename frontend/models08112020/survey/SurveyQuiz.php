<?php

namespace frontend\models\survey;

use Yii;

/**
 * This is the model class for table "survey_quiz".
 *
 * @property int $id
 * @property string $quiz_text
 * @property string $answer_type
 * @property int $created_by
 * @property string $created_date
 * @property int $is_deleted
 * @property int $sub_option
 * @property string $display_ans_type
 */
class SurveyQuiz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_quiz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz_text'], 'string'],
            [['created_by'], 'integer'],
            [['created_date'], 'safe'],
            [['answer_type', 'display_ans_type'], 'string', 'max' => 20],
            [['is_deleted', 'sub_option'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quiz_text' => 'Quiz Text',
            'answer_type' => 'Answer Type',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'is_deleted' => 'Is Deleted',
            'sub_option' => 'Sub Option',
            'display_ans_type' => 'Display Ans Type',
        ];
    }
}
