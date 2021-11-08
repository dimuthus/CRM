<?php

namespace frontend\models\cases;

use Yii;

/**
 * This is the model class for table "case_escalation".
 *
 * @property int $id
 * @property int $case_id
 * @property int $escalated_to
 * @property int $escalated_by
 * @property string $date_of_escalation
 * @property string $date_of_closure
 * @property int $closed_by
 * @property string $escalation_remarks
 *
 * @property CustomerCases $case
 */
class CaseEscalation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'case_escalation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_id', 'escalated_to', 'escalated_by', 'closed_by'], 'required'],
            [['case_id', 'escalated_to', 'escalated_by', 'closed_by'], 'integer'],
            [['date_of_escalation', 'date_of_closure'], 'safe'],
            [['escalation_remarks'], 'string'],
            [['case_id'], 'exist', 'skipOnError' => true, 'targetClass' => CustomerCases::className(), 'targetAttribute' => ['case_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_id' => 'Case ID',
            'escalated_to' => 'Escalated To',
            'escalated_by' => 'Escalated By',
            'date_of_escalation' => 'Date Of Escalation',
            'date_of_closure' => 'Date Of Closure',
            'closed_by' => 'Closed By',
            'escalation_remarks' => 'Escalation Remarks',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCase()
    {
        return $this->hasOne(CustomerCases::className(), ['id' => 'case_id']);
    }
	
	     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseEscalatedTo()
    {
        return $this->hasOne(User::className(), ['id' => 'escalated_to']);
    }
    
}
