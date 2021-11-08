<?php
namespace frontend\modules\api\controllers;
use Yii;
use frontend\modules\api\resource\CustomerResource;
use yii\data\ActiveDataProvider;
use frontend\modules\api\models\Customer;
use yii\db\Connection;
/**
 * Class PostController
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package frontend\controllers
 */
class CustomerController extends ActiveController
{
    public $modelClass = CustomerResource::class;
    
	
	
	
	
    public function actions()
    {
		$actions = parent::actions();
        unset($actions['index']);
        unset($actions['update']);
        return $actions;		
		//$encryptionKey = Yii::$app->params['encryptionKey'];
        //die('encryptionKey'.$encryptionKey);

        //die('sssssssssssssssss');
      // $actions = parent::actions();
       // $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
      // print_r($actions);
       //die('AAA');
       //return $actions;
    }
	
	public function actionCreate(){
		$mobile_number=\Yii::$app->request->post('mobile_number');
		$model = new Customer();
		$model->created_by=27;
		$mobile_number=\Yii::$app->request->post('mobile_number');
		$model->mobile_number=$mobile_number;
		$model->t_pin=\Yii::$app->request->post('t_pin');
		//die($mobile_number);
		$model->save();
		return $this->prepareDataProviderWithTpin();
		//die("insert here ");
	}
	
	public function actionIndex(){
	     return $this->prepareDataProvider();
	}
	
	public function actionVip(){
		$this->validate('cli');
	    $res=$this->prepareDataProvider()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','VIP']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
        return  $res;

	}
	public function actionLanguage(){
		$this->validate('cli');
		$res=$this->prepareDataProvider()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','LANG']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
        return  $res;

	}
	
	public function actionMobile(){
		$this->validate('cli');
        $res=$this->prepareDataProvider()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','REGISTERED']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
        return  $res;

	}
	public function actionTpinregister(){
		$this->validate('cli');
		$this->validate('tpin');
	    $res=$this->prepareDataProvider()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','REGISTRATION']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
        return  $res;

	}
	public function actionTpinvalidate(){
		//die('hdhdhdhdhd');
		$this->validate('cli');
		$this->validate('tpin');
	    $res=$this->prepareDataProviderWithTpin()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','t_pin']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
		return $res;

	}
	
	public function actionUpdatetpin(){
	$mobile_number=\Yii::$app->request->post('cli');
	   $tpin=\Yii::$app->request->post('tpin');
	   if ($mobile_number=="" AND $tpin=="" ){
		$error=array('error'=>true,'message'=>'cli and tpin required!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();

	   }
	   else if ($mobile_number=="" AND $tpin!="" ){
		$error=array('error'=>true,'message'=>'cli required!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit(); 
	   }
	   else if  ($mobile_number!="" AND $tpin=="" ){
		$error=array('error'=>true,'message'=>'tpin required!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();   
	   }
	   else{
		$encryptionKey = Yii::$app->params['encryptionKey'];
		//$sql="SELECT AES_DECRYPT(UNHEX('".$request_param."'),'".$encryptionKey."') AS tpin";
		//die($sql);
		
		// $queryDeMob=Yii::$app->db->createCommand("SELECT AES_DECRYPT(UNHEX('".$mobile_number."'),'".$encryptionKey."') AS mobile_number");
		// $resultDeMob = $queryDeMob->queryAll();
		 // $mobile_number=$resultDeMob[0]['mobile_number'];   
		
		// $queryDeTpin=Yii::$app->db->createCommand("SELECT AES_DECRYPT(UNHEX('".$tpin."'),'".$encryptionKey."') AS t_pin");
		// $resultDeTpin = $queryDeTpin->queryAll();
		 // $tpin=$resultDeTpin[0]['t_pin']; 		
		   
		$queryCli=Yii::$app->db->createCommand("SELECT count(mobile_number) as totM FROM customer WHERE mobile_number=:mobile_number")->bindValue(":mobile_number", $mobile_number);
		$result = $queryCli->queryAll();
        $rowCountM=(int)$result[0]['totM'];  
		$queryTpin=Yii::$app->db->createCommand("SELECT count(t_pin) as totT FROM customer WHERE t_pin=:t_pin")->bindValue(":t_pin", $tpin);
		$result = $queryTpin->queryAll();
        $rowCountT=(int)$result[0]['totT']; 
		
		//die ('rowCountM='.$rowCountM."rowCountT=".$rowCountT);
		if ($rowCountM==0 AND $rowCountT==0){
	    $error=array('error'=>true,'message'=>'Invalid cli and tpin!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();
		}
		// else if ($rowCountM !=0 AND $rowCountT==0){
		
	    // $error=array('error'=>true,'message'=>'Invalid tpin!');//['error'=>true,'message'=>'CLI isrequired!'];
        // echo json_encode($error);
		// exit();
		// }
		// else if ($rowCountM ==0 AND $rowCountT!=0){
	    // $error=array('error'=>true,'message'=>'Invalid cli!');//['error'=>true,'message'=>'CLI isrequired!'];
        // echo json_encode($error);
		// exit();
		// }
	   }
	   //$this->validate('cli');
	   $this->validateTpin('tpin');
	   $mobile_number=\Yii::$app->request->post('cli');
	   $tpin=\Yii::$app->request->post('tpin');
		\Yii::$app->db->createCommand("UPDATE customer SET t_pin=:t_pin WHERE mobile_number=:mobile_number")->bindValue(':mobile_number', $mobile_number)->bindValue(':t_pin', $tpin)->execute();
		$this->validate('tpin');
		$res=$this->prepareDataProviderWithTpin()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','t_pin']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
		return $res;
		//die('xxxxxxxxx');
	}
	
	public function actionUpdatelanguage(){
	
	   $mobile_number=\Yii::$app->request->post('cli');
	   $pl=\Yii::$app->request->post('pl');
	   if ($mobile_number=="" AND $pl=="" ){
		$error=array('error'=>true,'message'=>'cli and pl required!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();

	   }
	   else if ($mobile_number=="" AND $pl!="" ){
		$error=array('error'=>true,'message'=>'cli required!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit(); 
	   }
	   else if  ($mobile_number!="" AND $pl=="" ){
		$error=array('error'=>true,'message'=>'pl required!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();   
	   }
	   else{
		$encryptionKey = Yii::$app->params['encryptionKey'];
		//$sql="SELECT AES_DECRYPT(UNHEX('".$request_param."'),'".$encryptionKey."') AS tpin";
		//die($sql);
		
		// $queryDeMob=Yii::$app->db->createCommand("SELECT AES_DECRYPT(UNHEX('".$mobile_number."'),'".$encryptionKey."') AS mobile_number");
		// $resultDeMob = $queryDeMob->queryAll();
		 // $mobile_number=$resultDeMob[0]['mobile_number'];   
		
		// $queryDeTpin=Yii::$app->db->createCommand("SELECT AES_DECRYPT(UNHEX('".$tpin."'),'".$encryptionKey."') AS t_pin");
		// $resultDeTpin = $queryDeTpin->queryAll();
		 // $tpin=$resultDeTpin[0]['t_pin']; 		
		   
		$queryCli=Yii::$app->db->createCommand("SELECT count(mobile_number) as totM FROM customer WHERE mobile_number=:mobile_number")->bindValue(":mobile_number", $mobile_number);
		$result = $queryCli->queryAll();
        $rowCountM=(int)$result[0]['totM'];  
		$queryPl=Yii::$app->db->createCommand("SELECT count(preferred_language) as totPl FROM customer WHERE preferred_language=:pl")->bindValue(":pl", $pl);
		$result = $queryPl->queryAll();
        $rowCountPl=(int)$result[0]['totPl']; 
		
		//die ('rowCountM='.$rowCountM."rowCountT=".$rowCountT);
		if ($rowCountM==0 AND $rowCountPl==0){
	    $error=array('error'=>true,'message'=>'Invalid cli and pl!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();
		}
		else if ($rowCountM !=0 AND $rowCountPl==0){
	    $error=array('error'=>true,'message'=>'Invalid pl!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();
		}
		else if ($rowCountM ==0 AND $rowCountPl!=0){
	    $error=array('error'=>true,'message'=>'Invalid cli!');//['error'=>true,'message'=>'CLI isrequired!'];
        echo json_encode($error);
		exit();
		}
	   }
	
	  // $this->validate('cli');
	  // $this->validate('pl');
	   $mobile_number=\Yii::$app->request->post('cli');
	   $pl=\Yii::$app->request->post('pl');
		$query=\Yii::$app->db->createCommand("UPDATE customer SET preferred_language=:pl WHERE mobile_number=:mobile_number")->bindValue(':mobile_number', $mobile_number)->bindValue(':pl', $pl)->execute();
		$res=$this->prepareDataProvider()->getModels();
		$myresA=$res[0]->getAttributes(['mobile_number','LANG']);
		$myresJ=json_encode($myresA);
		$this->logdata($myresJ);
        return  $res;
		//die('xxxxxxxxx');
	}
    public function prepareDataProvider()
    {
		
	 $query=CustomerResource::find()->select([ 'id', 'mobile_number', 't_pin','CASE WHEN `vip_flag`="Y" THEN "Yes" ELSE "No" END AS VIP',
'CASE
WHEN `preferred_language`=1 THEN "Sinhala"
ELSE
CASE WHEN `preferred_language`=2 THEN "Tamil"
ELSE
CASE WHEN `preferred_language`=3 THEN "English"
ELSE
"Invalid"
END 
END
END AS LANG' ,
'CASE WHEN `mobile_number` !="" AND `t_pin` !="" THEN "Success" ELSE "Failed" END AS REGISTRATION',
'CASE WHEN `mobile_number` !="" AND (`t_pin` !="" AND `t_pin` !="25581E1482E02461B617D46E94D3A31A" ) THEN "Yes" ELSE "No" END AS REGISTERED',
'CASE WHEN `mobile_number`= `mobile_number` AND `t_pin` =`t_pin` THEN "Success" ELSE "Failed" END AS VALIDATE'])->andWhere(['mobile_number' => \Yii::$app->request->post('cli')]);
		
	// echo $query->createCommand()->sql;
	 //	var_dump($res);
	// die('ssssssssss');

		return new ActiveDataProvider(['query' => $query]);
	
         //if (isset(\Yii::$app->request->get('cusId')))
     // return new ActiveDataProvider(['query' => CustomerResource::find()->andWhere(['mobile_number' => \Yii::$app->request->post('cusId')])]);
        // else
        // return new ActiveDataProvider(['query' => Customer::findAll()]);
    }
	
	public function prepareDataProviderWithTpin()
    {
		
	 $query=CustomerResource::find()->select([ 'id', 'mobile_number', 't_pin','CASE WHEN `vip_flag`="Y" THEN "Yes" ELSE "No" END AS VIP',
'CASE 
WHEN `preferred_language`=1 THEN "Sinhala" 
 ELSE  
  CASE WHEN `preferred_language`=2 THEN "Tamil" 
  ELSE
  "English"
  END
 END AS LANG' ,
'CASE WHEN `mobile_number` !="" AND `t_pin` !="" THEN "Success" ELSE "Failed" END AS REGISTRATION',
'CASE WHEN `mobile_number` !="" THEN "Yes" ELSE "No" END AS REGISTERED',
'CASE WHEN `mobile_number`= `mobile_number` AND `t_pin` =`t_pin` THEN "Success" ELSE "Failed" END AS VALIDATE'])
->andWhere(['mobile_number' => \Yii::$app->request->post('cli')])
->andWhere(['t_pin' => \Yii::$app->request->post('tpin')]);		
	 //echo $query->createCommand()->sql;
	 //	var_dump($res);
	 //die('ssssssssss');

		return new ActiveDataProvider(['query' => $query]);
	
         //if (isset(\Yii::$app->request->get('cusId')))
     // return new ActiveDataProvider(['query' => CustomerResource::find()->andWhere(['mobile_number' => \Yii::$app->request->post('cusId')])]);
        // else
        // return new ActiveDataProvider(['query' => Customer::findAll()]);
    }
	
	
	
	public function validateTpin($param){
		
		$request_param=\Yii::$app->request->post($param);
		$error=[];
		if ($request_param==""){
			$error=array('error'=>true,'message'=>$param.' is required!');//['error'=>true,'message'=>'CLI isrequired!'];
			echo json_encode($error);
		    exit();
		}
		else{
		/*check new tpin length*/
		$encryptionKey = Yii::$app->params['encryptionKey'];
		$sql="SELECT AES_DECRYPT(UNHEX('".$request_param."'),'".$encryptionKey."') AS tpin";
		//die($sql);
		$queryDe=Yii::$app->db->createCommand("SELECT AES_DECRYPT(UNHEX('".$request_param."'),'".$encryptionKey."') AS tpin");
		$resultDe = $queryDe->queryAll();
		
        $tpin=$resultDe[0]['tpin'];
		if(!preg_match('/^[0-9]{4}$/', $tpin))
			$error=array('error'=>true,'message'=>'Invalid '.$param);
		else
			return true;
		}
				
		
		echo json_encode($error);
		exit();

	}
	
	
	public function validate($param){
		
		
		$fieldName="";
		switch ($param) {
		  case 'cli':
			$fieldName="mobile_number";
			break;
		  case 'pl':
			$fieldName="preferred_language";
			break;
		 case 'tpin':
			$fieldName="t_pin";
			break;
		
		}
		
		$request_param=\Yii::$app->request->post($param);
		$error=[];
		
		
		if ($request_param==""){
			$error=array('error'=>true,'message'=>$param.' is required!');//['error'=>true,'message'=>'CLI isrequired!'];
			echo json_encode($error);
		    exit();
		}
		else{
		
		$query=Yii::$app->db->createCommand("SELECT count(".$fieldName.") as tot FROM customer WHERE ".$fieldName."=:".$fieldName."")->bindValue(":".$fieldName."", $request_param);
		$result = $query->queryAll();
        $rowCount=(int)$result[0]['tot'];
		if ($rowCount==0)
			$error=array('error'=>true,'message'=>'Invalid '.$param);
		else
			return true;
		    //return $this->prepareDataProvider();
		
		
		echo json_encode($error);
		exit();
		//die('request_param='.$request_param);
		//$request_param=json_encode($request_param);
		//$url=Yii::$app->controller->action->uniqueId;
	  //\Yii::$app->db->createCommand("INSERT INTO api_log (url,request_param,response) VALUES ('$url','$request_param','$response')")->execute();

	 }
	}
	public function logdata($response){
		$request_param=\Yii::$app->request->post();
		$request_param=json_encode($request_param);
		$url=Yii::$app->request->url;//Yii::$app->controller->action->uniqueId;

		$ip=Yii::$app->getRequest()->getUserIP();
	  \Yii::$app->db->createCommand("INSERT INTO api_log (url,request_param,response,ip) VALUES ('$url','$request_param','$response','$ip')")->execute();

	}
   
}


