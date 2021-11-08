<?php

namespace frontend\models\survey;

use Yii;

/**
 * This is the model class for table "survey_response".
 *
 * @property int $id
 * @property int $request_id
 * @property int $question_id
 * @property int $sub_question_id
 * @property string $response
 * @property string $create_date
 */
class SurveyResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_response';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'question_id', 'sub_question_id'], 'integer'],
            [['create_date'], 'safe'],
            [['response'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request ID',
            'question_id' => 'Question ID',
            'sub_question_id' => 'Sub Question ID',
            'response' => 'Response',
            'create_date' => 'Create Date',
        ];
    }
}
