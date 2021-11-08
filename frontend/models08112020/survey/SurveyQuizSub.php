<?php

namespace frontend\models\survey;

use Yii;

/**
 * This is the model class for table "survey_quiz_sub".
 *
 * @property int $id
 * @property int $related_quiz_id
 * @property string $quiz_text
 * @property string $answer_type
 * @property int $created_by
 * @property string $created_date
 * @property int $is_deleted
 * @property string $display_ans_type
 */
class SurveyQuizSub extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_quiz_sub';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['related_quiz_id', 'created_by'], 'integer'],
            [['created_date'], 'safe'],
            [['quiz_text'], 'string', 'max' => 255],
            [['answer_type'], 'string', 'max' => 20],
            [['is_deleted'], 'string', 'max' => 4],
            [['display_ans_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'related_quiz_id' => 'Related Quiz ID',
            'quiz_text' => 'Quiz Text',
            'answer_type' => 'Answer Type',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'is_deleted' => 'Is Deleted',
            'display_ans_type' => 'Display Ans Type',
        ];
    }
}
