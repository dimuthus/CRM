<?php

namespace frontend\modules\survey\models;

use Yii;

/**
 * This is the model class for table "crm_survey_question".
 *
 * @property integer $id
 * @property string $text
 * @property string $updated_date
 * @property integer $question_type_id
 */
class CrmSurveyQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $item_status;
    public static function tableName()
    {
        return 'crm_survey_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['updated_date'], 'safe'],
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
            'text' => 'Question Text',
            'updated_date' => 'Updated Date',
            'question_type_id' => 'Question Type ID',
            'questionType.name'=>'Question Type',

        ];
    }

       public function getQuestionType()
    {
        return $this->hasOne(CrmSurveyQuestionType::className(), ['id' => 'question_type_id']);
    }

    public function setItemStatus()
    {
      $this->item_status = $this->is_deleted;
    }

    public function getItemStatus()
    {
      if ($this->item_status==0)
          return 'Active';
      else
          return 'Inactive';
    }

}
