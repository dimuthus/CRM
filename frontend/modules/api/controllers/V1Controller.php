<?php

namespace frontend\modules\api\controllers;

class V1Controller extends \yii\web\Controller
{
    public function actionVip()
    {
      
	$token = "4HB1SK6ELb-QenC7nVsgazLUJ6axlhSQ"; // Get your token from a cookie or database
	//$data = "cli=6532BE58A51CB20118DA3D77F90488EE"; // Array of data with a trigger
    $data =http_build_query(["cli"  => "8B93EF7A7A7A817A66DACBA6A5E3D38B"]); // this also working
	//$data =http_build_query(array("cli"  => "6532BE58A51CB20118DA3D77F90488EE66")); not working
	//$data =json_encode(array("cli"  => "6532BE58A51CB20118DA3D77F90488EE66")); not working
    //var_dump($data);
	//$request = jwt_request($token,null); 
	$url="http://10.1.27.231/boc_crm/api/customer/vip";
	$request =$this->jrequest($token,$data,$url); 
	return $request;
    //return $this->render('index');
    }
	
	public function actionLanguage()
    {
      
	$token = "4HB1SK6ELb-QenC7nVsgazLUJ6axlhSQ"; // Get your token from a cookie or database
	//$data = "cli=6532BE58A51CB20118DA3D77F90488EE"; // Array of data with a trigger
    $data =http_build_query(["cli"  => "8B93EF7A7A7A817A66DACBA6A5E3D38B"]); // this also working
	$url="http://10.1.27.231/boc_crm/api/customer/language";
	$request =$this->jrequest($token,$data,$url); 
	return $request;
    //return $this->render('index');
    }
	
	public function actionMobile()
    {
      
	$token = "4HB1SK6ELb-QenC7nVsgazLUJ6axlhSQ"; // Get your token from a cookie or database
	//$data = "cli=6532BE58A51CB20118DA3D77F90488EE"; // Array of data with a trigger
    $data =http_build_query(["cli"  => "8B93EF7A7A7A817A66DACBA6A5E3D38B"]); // this also working
	$url="http://10.1.27.231/boc_crm/api/customer/mobile";
	$request =$this->jrequest($token,$data,$url); 
	return $request;
    //return $this->render('index');
    }
	
	public function actionTpinregister()
    {
      
	$token = "4HB1SK6ELb-QenC7nVsgazLUJ6axlhSQ"; // Get your token from a cookie or database
	//$data = "cli=6532BE58A51CB20118DA3D77F90488EE"; // Array of data with a trigger
    $data =http_build_query(["cli"  => "8B93EF7A7A7A817A66DACBA6A5E3D38B","tpin"=>"232445454545"]);  // this also working
	$url="http://10.1.27.231/boc_crm/api/customer/tpinregister";
	$request =$this->jrequest($token,$data,$url); 
	return $request;
    //return $this->render('index');
    }
	
	public function actionTpinvalidate()
    {
      
	$token = "4HB1SK6ELb-QenC7nVsgazLUJ6axlhSQ"; // Get your token from a cookie or database
	//$data = "cli=6532BE58A51CB20118DA3D77F90488EE"; // Array of data with a trigger
    $data =http_build_query(["cli"  => "8B93EF7A7A7A817A66DACBA6A5E3D38B","tpin"=>"232445454545"]); // this also working
	$url="http://10.1.27.231/boc_crm/api/customer/tpinvalidate";
	$request =$this->jrequest($token,$data,$url); 
	return $request;
    //return $this->render('index');
    }
	
	
	
	public function jrequest($token,$data,$url){
		
		
		$ch = curl_init();
		$authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
     
		$server_output = curl_exec ($ch);
		
		//   die('vvvv');
		curl_close ($ch);
		// further processing ....
		return $server_output;
	}

}
