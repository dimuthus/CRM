<?php
/**
 * User: TheCodeholic
 * Date: 3/7/2020
 * Time: 9:36 AM
 */

namespace frontend\modules\api\resource;
use frontend\modules\api\models\Customer;
use Yii;


/**
 * Class NoteResource
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\api\resources
 */
class CustomerResource extends Customer
{
 public function fields()
    {
		$url=Yii::$app->request->url; //Yii::$app->controller->getAction()->getId() ;

		if (strpos($url, "api/customer/vip") !==false){
            return ['mobile_number','VIP'];
        }
		else if ((strpos($url, "api/customer/language") !==false)  OR (strpos($url, "api/customer/updatelanguage") !==false)){
			 return ['mobile_number','LANG'];
		}
		else if(strpos($url, "api/customer/mobile") !==false){
			 return ['mobile_number','REGISTERED'];
		}
		else if((strpos($url, "api/customer/tpinregister") !==false) OR (strpos($url, "api/customer/updatetpin") !==false)){
			 return ['mobile_number','REGISTRATION'];
		}
		else if(strpos($url, "api/customer/tpinvalidate") !==false ){
			return ['mobile_number','VALIDATE'];
		}
		else{
            return ['id', 'mobile_number', 't_pin','VIP','LANG','REGISTRATION','REGISTERED','VALIDATE'];
        }
      
    }
    
   /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalutation()
    {
        return $this->hasOne(Salutation::className(), ['id' => 'salutation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerStatus()
    {
        return $this->hasOne(CustomerStatus::className(), ['id' => 'customer_status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerType()
    {
        return $this->hasOne(CustomerType::className(), ['id' => 'customer_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender0()
    {
        return $this->hasOne(Gender::className(), ['id' => 'gender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['customer_id' => 'id']);
    }  
    
}
