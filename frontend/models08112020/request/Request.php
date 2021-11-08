<?php

namespace frontend\models\request;

use frontend\models\Country;
use frontend\modules\tools\models\user\User;
use frontend\models\customer\Customer;
use frontend\models\product\Product;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "service_request".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $service_request_id
 * @property integer $service_request_type
 * @property integer $detail_type
 * @property integer $additional_detail
 * @property integer $supplemental_detail
 * @property integer $service_request_status
 * @property integer $escalated_to
 * @property integer $priority
 * @property string $country
 * @property integer $service_center
 * @property integer $created_by
 * @property integer $closed_by
 * @property string $onsite_appointment_datetime
 * @property string $device
 * @property string $enhancement
 * @property string $software
 * @property string $creation_datetime
 * @property string $last_modified_datetime
 * @property string $closed_datetime
 */
class Request extends \yii\db\ActiveRecord
{

    protected $escalation_statuses = [2,6,7,8,13,14,17];
    protected $service_center_statuses = [16,17];
    protected $closed_statuses = [1,3,4,12];

    public $productIds = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'service_request_type', 'detail_type', 'additional_detail', 'supplemental_detail', 'service_request_status', 'escalated_to', 'priority', 'service_center','country', 'created_by', 'device', 'enhancement', 'software'], 'integer', 'message'=>''],
            [['service_request_type', 'detail_type', 'additional_detail', 'supplemental_detail','service_request_status', 'priority', 'country'], 'required', 'message'=>''],
            [['creation_datetime', 'last_modified_datetime', 'closed_datetime', 'onsite_appointment_datetime', 'productIds'], 'safe'],
            [['service_request_id'], 'string', 'max' => 50],
            [['escalated_to'], 'escalatedRequired', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['service_center'], 'centerRequired','skipOnEmpty' => false, 'skipOnError' => false],  
            [['productIds'], 'productRequired','skipOnEmpty' => false, 'skipOnError' => false],  
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'service_request_id' => 'Service Request ID',
            'service_request_type' => 'Service Request Type',
            'detail_type' => 'Detail Type',
            'additional_detail' => 'Additional Detail',
            'supplemental_detail' => 'Supplemental Detail',
            'service_request_status' => 'Service Request Status',
            'escalated_to' => 'Escalated To',
            'priority' => 'Priority',
            'country' => 'Country',
            'service_center' => 'Service Center',
            'created_by' => 'Created By',
            'closed_by' => 'Closed By',
            'creation_datetime' => 'Creation Date & Time',
            'last_modified_datetime' => 'Last Update Date & Time',
            'closed_datetime' => 'Closed Date & Time',
            'onsite_appointment_datetime' => 'Onsite Appointment Date & Time',
            'customer.full_name' => 'Customer Name',
            'type.name' => 'Service Request Type',
            'detail.name' => 'Detail Type',
            'additional.name' => 'Additional Detail',
            'supplemental.name' => 'Supplemental Detail',
            'status.name' => 'Service Request Status',
            'device' => 'Device',
            'deviceObj.name' => 'Device',
            'enhancement' => 'Enhancement',
            'enhancementObj.name' => 'Enhancement',
            'software' => 'Software',
            'softwareObj.name' => 'Software',
            'escalated.username' => 'Escalated to',
            'prioritytitle.name' => 'Priority',
            'countrytitle.name' => 'Country',
            'center.name' => 'Service Center',
            'creator.username' => 'Created by',
            'closer.username' => 'Closed by',
            'productIds' => 'Products'

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function getProducts()
    {
        // Return entities related to this location
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
                    ->viaTable(RequestProducts::tableName(), ['request_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(RequestType::className(), ['id' => 'service_request_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasOne(RequestDetailType::className(), ['id' => 'detail_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditional()
    {
        return $this->hasOne(RequestAdditionalType::className(), ['id' => 'additional_detail']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplemental()
    {
        return $this->hasOne(RequestSupplementalType::className(), ['id' => 'supplemental_detail']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(RequestStatus::className(), ['id' => 'service_request_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEscalated()
    {
        return $this->hasOne(User::className(), ['id' => 'escalated_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrioritytitle()
    {
        return $this->hasOne(RequestPriority::className(), ['id' => 'priority']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountrytitle()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCenter()
    {
        return $this->hasOne(RequestServiceCenter::className(), ['id' => 'service_center']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceObj()
    {
        return $this->hasOne(RequestDevice::className(), ['id' => 'device']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnhancementObj()
    {
        return $this->hasOne(RequestEnhancement::className(), ['id' => 'enhancement']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSoftwareObj()
    {
        return $this->hasOne(RequestSoftware::className(), ['id' => 'software']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCloser()
    {
        return $this->hasOne(User::className(), ['id' => 'closed_by']);
    }

    public function escalatedRequired($attribute_name, $params)
    {  
        
        if (in_array($this->service_request_status, $this->escalation_statuses) && empty($this->$attribute_name)) {
            $this->addError($attribute_name, Yii::t('user', ""));
            return false;
        }

        return true;
    }

    public function centerRequired($attribute_name, $params)
    {  
        if (in_array($this->service_request_status, $this->service_center_statuses) && empty($this->$attribute_name)) {
            $this->addError($attribute_name, Yii::t('user', ""));
            return false;
        }

        return true;
    }

    public function productRequired($attribute_name, $params)
    {  
        if ($this->service_request_type == 5 && empty($this->$attribute_name)) {
            $this->addError($attribute_name, Yii::t('user', ""));
            return false;
        }

        return true;
    }

    public function beforeSave($insert)
    {
        /*if (parent::beforeSave($insert)) {
            
            return true;
        } else {
            return false;
        }*/
        if($this->service_request_status == 3) {
            $this->closed_by = Yii::$app->user->identity->id;
            $this->closed_datetime = (new \DateTime('now', new \DateTimeZone('Asia/Kuala_Lumpur')))->format('Y-m-d H:i:s');
        }
        return true;
    }


    public function afterSave($insert, $changedAttributes)
    {
        $actualProducts = [];
        $productExists = 0; 

        if (sizeof($actualProducts = RequestProducts::find()
            ->andWhere("request_id = $this->id")
            ->asArray()
            ->all()) != 0) {
                $actualProducts = ArrayHelper::getColumn($actualProducts, 'product_id');
                $productExists = 1; 
        }

        if (!empty($this->productIds)) {
            foreach ($this->productIds as $id) {
                $actualProducts = array_diff($actualProducts, [$id]);
                $r = new RequestProducts();
                $r->request_id = $this->id;
                $r->product_id = $id;
                if(!in_array($id, $this->getProductIds()))
                    $r->save();
            }
        }

        if ($productExists == 1) {
            foreach ($actualProducts as $remove) {
                $r = RequestProducts::findOne(['product_id' => $remove, 'request_id' => $this->id]);
                $r->delete();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }
     
    // You will need a getter for the current set o Authors in this Book
    public function getProductIds()
    {
        $this->productIds = \yii\helpers\ArrayHelper::getColumn(
        $this->getProducts()->asArray()->all(),
        'id'
        );

        return $this->productIds;
    }

}
 