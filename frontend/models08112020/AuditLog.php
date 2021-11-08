<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audit_log".
 *
 * @property integer $id
 * @property string $module_name
 * @property string $old_row_data
 * @property string $new_row_data
 * @property string $dml_type
 * @property string $dml_timestamp
 * @property string $dml_created_by
 */
class AuditLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audit_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_name', 'dml_type', 'dml_timestamp', 'dml_created_by'], 'required'],
            [['old_row_data', 'new_row_data', 'dml_type'], 'string'],
            [['dml_timestamp'], 'safe'],
            [['module_name', 'dml_created_by'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_name' => 'Module Name',
            'old_row_data' => 'Old Row Data',
            'new_row_data' => 'New Row Data',
            'dml_type' => 'Dml Type',
            'dml_timestamp' => 'Dml Timestamp',
            'dml_created_by' => 'Dml Created By',
        ];
    }
}
