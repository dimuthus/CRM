<?php

namespace frontend\models;

use Yii;
use frontend\modules\tools\models\user\User;

/**
 * This is the model class for table "logadm".
  * @property string $dml_type
 * @property string old_row_data
 * @property string new_row_data
 * @property string $module_name
 * @property integer $dml_created_by
 * @property string ip_address
 
 */
class Logadm extends \yii\db\ActiveRecord
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
            [['dml_created_by'], 'integer'],
            [['dml_type','old_row_data','module_name'], 'safe'],
            [['ip_address'], 'string', 'max' => 250]
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'module_name' => 'Module',
            'old_row_data' => 'Old Row Data',
            'new_row_data' => 'New Row Data',
            'dml_type' => 'Dml Type',
            'dml_timestamp' => 'DML Timestamp',
            'dml_createdBy.username' => 'Created By Name',
            'ip_address' => 'IP Adress',
           
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'dml_created_by']);
    }

    public static function createInsertLog($data,$model){

        // var_dump($data);
		// die('dddddddddddddddddddddd');
         $keys =array_keys($data);
         $tablename=$keys[1];
         $dataUpdate= $data[$tablename];
            

            $firstItem = array('id' => $model->id);
            $finaldata= $firstItem ; //+ $dataUpdate;
            $finaldata= json_encode($finaldata);
        


            $logmodel= new Logadm();
            $logmodel->module_name= $tablename;
            $logmodel->old_row_data=Null;
            
            $logmodel->new_row_data=$finaldata;
            $logmodel->dml_type="INSERT";
            $logmodel->dml_created_by="7";
            if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;
            if($logmodel->save()){

                return true;
            }
            return false;


    }


      


public static function createInsertLogWm($data,$id,$type){
    
      $keys =array_keys($data);
      $tablename="";
         if(strtolower($type)=="remove"){

          $tablename=$keys[2];
         
         } else  $tablename=$keys[1]; 
          $dataUpdate= $data[$tablename];
          

            $firstItem = array('id' => $id);
            $finaldata= $firstItem + $dataUpdate;
            $finaldata= json_encode($finaldata);
        


            $logmodel= new Logadm();
            $logmodel->module_name= $tablename;
            $logmodel->old_row_data=Null;
            
            $logmodel->new_row_data=$finaldata;
            $logmodel->dml_type=$type;
            if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;
            if($logmodel->save()){
          
                return true;
            }

            return false;


    }


     public static function createViewLog($entity,$data){

        $logmodel= new Logadm();
            $logmodel->module_name= $entity;
            $logmodel->old_row_data=Null;
            $logmodel->new_row_data=$data;
            $logmodel->dml_type="VIEW";
			if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }            $logmodel->ip_address= Yii::$app->getRequest()->userIP;
            if($logmodel->save()){

                return true;
            }
            return false;


    }

    public static function createDeleteLog($entity,$data){

			$logmodel= new Logadm();
            $logmodel->module_name= $entity;
            $logmodel->old_row_data=Null;
            $logmodel->new_row_data=$data;
            $logmodel->dml_type="DELETE";
			if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }            $logmodel->ip_address= Yii::$app->getRequest()->userIP;
            if($logmodel->save()){

                return true;
            }
            return false;


    }




     public static function createOtherInsertLog($entity,$model){

     


            $logmodel= new Logadm();
            $logmodel->module_name= $entity;
            $logmodel->old_row_data=Null;
            $logmodel->new_row_data=Null;
            $logmodel->dml_type="INSERT";
            if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;
            if($logmodel->save()){

                return true;
            }
            return false;


    }

public static function createUpdateLog($oldData,$data,$model,$type="UPDATE"){

// $a=$model::model()->tableSchema->name;
// var_dump($a);
// die();

$connection = Yii::$app->getDb();
          
        $keys =array_keys($data);
         $tablename=$keys[1];
         
         $tablenamefinal = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $tablename));



             $dataUpdate= $data[$tablename];

$command = $connection->createCommand("
    SELECT * FROM ".$tablenamefinal." WHERE id = :id ", [':id' => $model->id]);

$result= $command->queryAll();

$oldData=json_encode($oldData);

            $firstItem = array('id' => $model->id);
            $finaldata= $firstItem + $dataUpdate;
            $finaldata= json_encode($finaldata);
            


            $logmodel= new Logadm();
            $logmodel->module_name= $tablename;
            $logmodel->old_row_data=$oldData;
            $logmodel->new_row_data=$finaldata;
            $logmodel->dml_type=$type;
            if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;

            

              if($logmodel->save()){

                return true;
              }

              return false;


}



public static function createUpdateLogUser($oldData,$newData,$type="UPDATE"){

$oldData=json_encode($oldData);

           
       
            $finaldata= json_encode($newData);
            


            $logmodel= new Logadm();
            $logmodel->module_name= "User";
            $logmodel->old_row_data=$oldData;
            $logmodel->new_row_data=$finaldata;
            $logmodel->dml_type=$type;
             if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;

            

              if($logmodel->save()){

                return true;
              }

              return false;


}


public static function createLogDDL($modelName,$oldData,$newData,$type){

$oldData=json_encode($oldData);

           
       
            $finaldata= json_encode($newData);
            


            $logmodel= new Logadm();
            $logmodel->module_name= $modelName;
            $logmodel->old_row_data=$oldData;
            $logmodel->new_row_data=$finaldata;
            $logmodel->dml_type=$type;
            if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;

            

              if($logmodel->save()){

                return true;
              }

              return false;


}

public static function createUpdateLogRule($ruleName,$oldData,$newData,$type="UPDATE"){

// $a=$model::model()->tableSchema->name;
// var_dump($a);
// die();

             $data = array('prevValue' => $oldData, 'newValue'=>$newData);
          
              $data=json_encode($data);


            $logmodel= new Logadm();
            $logmodel->module_name= $ruleName;
            $logmodel->old_row_data=Null;
            $logmodel->new_row_data=$data;
            $logmodel->dml_type=$type;
			
            if(!Yii::$app->user->isGuest){
            $logmodel->dml_created_by=Yii::$app->user->identity->id;  
            }
			
            $logmodel->ip_address= Yii::$app->getRequest()->userIP;



              if($logmodel->save(false)){

                return true;
              }

              return false;


}

    
}
