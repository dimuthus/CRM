<?php
namespace frontend\controllers;
/*
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\helpers\Json;
*/

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\helpers\Json;

use frontend\models\distribution\ContactDistribution;
use frontend\models\distribution\Redistribution;
use frontend\models\campaign\CustomerCampaign;
use frontend\models\campaign\Campaign;
use frontend\models\customer\Customer;
use frontend\modules\tools\models\user\User;

class DistributionController extends \yii\web\Controller
{
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
			  'access' => [
            'class' => AccessControl::className(),
            'rules' => [

                [
                    'actions' => ['distribute'],
                    'allow' => true,
                    'roles' => ['@'], // you can use matchCallback to create  more powerful  check 
                ],
            ],
        ],

        ];
    }

    public function actionRedistribute()
    {
				$this->logActivity('Re-Distribution');

		$model = new CustomerCampaign();

		if ($model->load(Yii::$app->request->post()) ) {

        }

        return $this->render('redistribute',[
            'model' => $model,
        ]);
    }

	/* public function actionDistribute()
    {
		$model = new ContactDistribution();

	//	Yii::$app->response->format = Response::FORMAT_JSON;

		$customerlistquery = new Query;
		$query = new Query;
	    $query = User::find()
			->where("role_id = 'Agent' AND status_id = 1")
			->orderby('username');

		$sort = [
		'defaultOrder' => ['username'=>SORT_ASC],
		'attributes' => [
			'username'=> [
				'asc' => ['username' => SORT_ASC],
				'desc' => ['username' => SORT_DESC],
				'default' => SORT_DESC
			],
			]
	    ];
		$agentDataProvider = new ActiveDataProvider([
			'pagination' => ['pageSize'=>10],
			'query' => $query,
			'sort' => $sort
		]);
		$customerDataProvider = new ActiveDataProvider([
			'pagination' => ['pageSize'=>5],
			'query' => Customer::find()->orderby('first_name ASC'),
			'sort' => false
		]);


		if (Yii::$app->request->get('refresh-widget')){
			$campaignid = Yii::$app->request->get('campaign_id');
			$crieteriaquery = new Query;
			$campaigncrieteria = $crieteriaquery->select('crieteria')->from('campaign')->where(['id'=> $campaignid])->all();
			$crieteriadata = $campaigncrieteria[0]['crieteria'];

			$crieteriajson = json_decode($crieteriadata, true);

			//$properJsonobj = serializeArraytoJson($campaigncrieteria);

			/*foreach ($crieteriajson as $k) {
				if($k['name'] == '_csrf'){

				}
				else{
				  echo $k['name']. ' : '.$k['value'];
				  echo "<br>";
				}
			}
			$wherecondition="";
			$age_group = array(); $ag = 0;
			$city = array(); $c=0;
			$race = array(); $r=0;
			$gender = array(); $g=0;
			$nationality = array(); $n = 0;
			$source = array(); $s=0;
			$state = array(); $st=0;
			$smoker = array(); $sm = 0;
			$product = array(); $p = 0;
			foreach($crieteriajson as $cj){
				switch($cj['name']){
					case 'age_group_id[]':  $age_group[++$ag]=$cj['value'];
											break;
					case 'race[]' :	$race[++$r] =  $cj['value'];
											break;
					case 'gender[]' : $gender[++$g] = $cj['value'];
											break;
					case 'nationality[]' : $nationality[++$n]=$cj['value'];
											break;
					case 'source[]' : $source[++$s]=$cj['value'];
											break;
					case 'city[]' : $city[++$c]=$cj['value'];
											break;
					case 'state[]': $state[++$st]=$cj['value'];
											break;
					case 'smoker[]': $smoker[++$sm]=$cj['value'];
											break;
					case 'product[]': $product[++$p]=$cj['value'];
											break;
					}

				}
				//print_r($age_group);var_dump($race);var_dump($gender);var_dump($nationality);var_dump($source);
				//var_dump($city);var_dump($state);var_dump($smoker);var_dump($product);
				$wherecondition .= " ( ";
				foreach($age_group as $ag){
					$wherecondition .= "age_group=".$ag." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($race as $r){
					$wherecondition .= "race=".$r." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($gender as $g){
					$wherecondition .= "gender=".$g." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($nationality as $n){
					$wherecondition .= "nationality=".$n." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($source as $s){
					$wherecondition .= "cds.source_id=".$s." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($city as $c){
					$wherecondition .= "city=".$c." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($state as $s){
					$wherecondition .= "state=".$s." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($smoker as $s){
					$wherecondition .= "csd.smoker=".$s." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= ") AND (";

				foreach($product as $p){
					$wherecondition .= "product1=".$p." OR ";
				}
				$wherecondition = substr($wherecondition, 0, -3);
				$wherecondition .= " ) ";

				//echo $wherecondition;


				$customerlistquery->select('c.customer_id, c.salutation_id, c.first_name, c.last_name,  age_group,gender,race,nationality,cds.source_id, csd.product1, city, state, csd.smoker')
								->from('customer c')
								->leftJoin('customer_data_source cds','c.id = cds.customer_id')
								->leftJoin('customer_smoking_details csd','c.id = csd.customer_id')
								->where($wherecondition)->distinct();
				$customerlistcount = $customerlistquery->count();

				$customerDataProvider = new ActiveDataProvider([
					'pagination' => ['pageSize'=>5],
					'query' => $customerlistquery,
					'sort' => false
				]);
				//var_dump($customerDataProvider);
				//die();
				return $this->renderAjax('customer_distribution_list',[
							'customerDataProvider'=>$customerDataProvider,
							'agentDataProvider'=>$agentDataProvider,
							'noofcustomers' => $customerlistcount,
				]);

		}
		// something
		else if ($model->load(Yii::$app->request->post()) ){

			$campaignid = $model->campaign_id;
			$agent_ids = $_POST['agent_ids'];
			echo "campaign_id : ". $campaignid;
			var_dump($agent_ids);
			die("here in post");

		}
		else{
			return $this->render('distribute',[
				'model' => $model,
				'agentDataProvider'=>$agentDataProvider,
				'customerDataProvider'=>$customerDataProvider,
				'noofcustomers' => 1,
			]);
		}



    }*/

	public function actionDistribute()
    {
		
		$this->logActivity('Distribution');
       if(Yii::$app->user->identity->id ==null){

        return $this->redirect(array('user/login'));

       }


	  $model = new ContactDistribution();
	//	Yii::$app->response->format = Response::FORMAT_JSON;
 	if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
    {
		$campid = $_POST['ContactDistribution']['campaign_id'];
			$noofcust = $_POST['ContactDistribution']['no_of_customers'];
			//echo "no of customemrs=".$noofcust."------------";
			//echo "campaign id = ".$campid."------------";
			
			//$agent_ids = $_POST['selection'];
			if (isset($_POST['selection'])){
			$agent_ids = $_POST['selection'];
		} else {
			$agent_ids =0;
		
		}
			$customerDataProvider = self::getcustomerDataProvider($campid);
			$customerdata = $customerDataProvider->getModels();
			$customerids = [];
			foreach($customerdata as $row){
				$customerids[] = $row['customer_id'];
			}
			$cd_model = new ContactDistribution();
			//get no. of agents
			//for loop no.of agents
			//inner for loop no.of customers
			$sizeofagents = sizeof($agent_ids);
			$sizeofcustomers = sizeof($customerids);
			$trimmedcustlist = array_slice($customerids, 0, $sizeofagents*$noofcust);
			//var_dump($trimmedcustlist);
			//echo "\n size of agents ::: ".$sizeofagents."<br\>";
			//echo "\n size of cust ::: ".$noofcust."<br\>";
			//var_dump($agent_ids);
			//die('GGGGGGGGG');
			$id = 0; $aid = 0;
			for($i=0; $i<$sizeofagents; $i++){
			//	echo "entered for loop for agents. agent :: ". $i;
				for($assigned=1; $assigned<=$noofcust; $assigned++){
				//	echo "entered for loop for no of customers...".$noofcust;
					if(sizeof($customerids) != NULL){
						//echo "entered if condition";
						$cd_model = new ContactDistribution();
						$customer = $customerids[$id];
						$cd_model->agent_id = $agent_ids[$i];
						$cd_model->customer_id = $customer;
						$cd_model->distributed_by = Yii::$app->user->identity->id;
						$cd_model->campaign_id = $campid;
						$cd_model->no_of_customers = $noofcust;
						$cd_model->save();
						if (($key = array_search($customer, $customerids)) !== false) {
							unset($customerids[$key]);
							//echo "\nleft over customer ids: ";
							//var_dump($customerids);
						}
						$id++;
						//$aid++;
					}

				}
			}
		   
			return $this->redirect(['/myinbox']);

			// return $this->render('distribute',[
				// 'model' => $cd_model,
				// 'agentDataProvider'=>$agentDataProvider,
				// 'customerDataProvider'=>$customerDataProvider,
				// 'noofcustomers' =>$noofcust,
				//'customerids' => $customerrows,
			// ]);
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		  return \yii\widgets\ActiveForm::validate($model);

    }

		$query = new Query;
	    $query = User::find()->where("role_id = 'Agent' AND status_id = 1")->orderby('username');

		$sort = [
		'defaultOrder' => ['username'=>SORT_ASC],
		'attributes' => [
			'username'=> [
				'asc' => ['username' => SORT_ASC],
				'desc' => ['username' => SORT_DESC],
				'default' => SORT_DESC
			],
			]
	    ];
		$agentDataProvider = new ActiveDataProvider([
			'pagination' => ['pageSize'=>10],
			'query' => $query,
			'sort' => $sort
		]);
		
		 $data = Yii::$app->request->post();
				 $selection= null;

		if (Yii::$app->request->get('refresh-widget')){

			$campaignid = Yii::$app->request->get('campaign_id');
			$customerDataProvider2 = self::getcustomerDataProvider($campaignid);
			$customerlistcount = $customerDataProvider2->getTotalCount();
			return $this->renderAjax('customer_distribution_list',[
						'customerDataProvider'=>$customerDataProvider2,
						'agentDataProvider'=>$agentDataProvider,
						'noofcustomers' => $customerlistcount,
						//'customerids' => $customerrows,
			]);

		}
		
		else{
			
			
			
			//$campid = $_POST['ContactDistribution']['campaign_id'];
			//$noofcust = $_POST['ContactDistribution']['no_of_customers'];
			
			$campaignid = Yii::$app->request->get('campaign_id');
			$customerDataProvider2 = self::getcustomerDataProvider($campaignid);
			return $this->render('distribute',[
				'model' => $model,
				'agentDataProvider'=>$agentDataProvider,
				'customerDataProvider'=>$customerDataProvider2,
				'noofcustomers' =>0,
				//'customerids' => $customerrows,
			]);
		}



    }
	/// dummy changes
	protected function getcustomerDataProvider($campaignid)
	{
		    $customerlistquery = new Query;
			$wherecondition="1=1";
			$customerlistquery->select('customer_id,mobile_number')->from('undistributed_list')->andWhere(['agent_id' => null])->andWhere(['campaign_id' => $campaignid])->all();
			$customerDataProvider = new ActiveDataProvider([
					'pagination' =>false,// ['pageSize'=>5],
					'query' => $customerlistquery,
					'sort' => false
				]);
			//echo $customerlistquery->createCommand()->getRawSql();
			//die('XXXX');
			return $customerDataProvider;

	}
	public function logActivity($moduleName){
		$ip=Yii::$app->getRequest()->getUserIP();
		$dml_created_by=Yii::$app->user->identity->id;
	  \Yii::$app->db->createCommand("INSERT INTO audit_log (module_name,dml_timestamp,dml_created_by,ip_address) VALUES ('$moduleName',NOW(),$dml_created_by,'$ip')")->execute();
		
	}
	

}
?>
