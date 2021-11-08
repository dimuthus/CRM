<?php

namespace frontend\controllers;

use Yii;
use frontend\models\distribution\Redistribution;
use frontend\models\distribution\ContactDistribution;
use frontend\models\distribution\RedistributionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use frontend\models\customer\Customer;
use	yii\bootstrap\Alert;
/**
 * RedistributionController implements the CRUD actions for Redistribution model.
 */
class RedistributionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Redistribution models.
     * @return mixed
     
    public function actionIndex()
    {
        $searchModel = new RedistributionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
*/
    /**
     * Displays a single Redistribution model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
 */
    /**
     * Creates a new Redistribution model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new ContactDistribution();
        $rd_model = new Redistribution();
		$customerDataProvider = new ActiveDataProvider([
			'pagination' => ['pageSize'=>5],
			'query' => Customer::find()->orderby('full_name ASC'),
			'sort' => false
		]);
		//	$noofcustomers = $customerDataProvider->getTotalCount();
		if (Yii::$app->request->get('refresh-widget')){
			
			$campaignid = Yii::$app->request->get('campaign_id');
			$agentid = Yii::$app->request->get('agent_id');
			
			$customerlistquery = new Query;
			$customerlistquery->select('customer_id')->from('contact_distribution')
			->where(['campaign_id'=> $campaignid])
			->andWhere(['agent_id'=>$agentid])
			->all();
			
			//var_dump($customerlistquery);
			$customerDataProvider = new ActiveDataProvider([
					'pagination' => ['pageSize'=>5],
					'query' => $customerlistquery,
					'sort' => false
				]);
			$noofcustomers = $customerDataProvider->getTotalCount();
			//echo "no of customers ".$noofcustomers;
			//die();
		/*	$rd_model = new Redistribution();
			$model = new ContactDistribution();
			$model->campaign_id = $campaignid;
			$model->agent_id = $agentid;*/
			return $this->renderAjax('distributed_list',[
					//	'customerDataProvider'=>$customerDataProvider,
						'noofcustomers'=>$noofcustomers,
					//	'rd_model' => $rd_model,
					//	'model' => $model,
						
			]);
		}
		
        else if ($model->load(Yii::$app->request->post())) {
            
	        var_dump($_POST);
			// var_dump($model);
			$redis_agent_id = $_POST['Redistribution']['agent_id'];
		    //die('dddddddddddddddddddddd'.$redis_agent_id);
			$campaignid = $model->campaign_id;
			$agentid = $model->agent_id;
			$noofcust = $model->no_of_customers;
			
			$model->delete(); // the model was being saved partially, that's why deleting it. 
		//	var_dump($model);
			$customerlistquery = new Query;
			$customerlistquery->select('id')->from('contact_distribution')
			->where(['campaign_id'=> $campaignid])
			->andWhere(['agent_id'=>$agentid])
		//	->andWhere(['!=','customer_id', 'NULL'])
			->all();
			//echo $customerlistquery->createCommand()->getRawSql();
			$customerDataProvider = new ActiveDataProvider([
					'pagination' => ['pageSize'=>5],
					'query' => $customerlistquery,
					'sort' => false
				]);
				
			$cust_list = $customerDataProvider->getModels();
			
			$custlistids = [];
			foreach($cust_list as $row){
				$custlistids= $row['id'];
			}
			
		    //var_dump($custlistids[0]);
			
		    //echo "customer list ids \n";
			//echo $noofcust;
			//die('fffffffffffffffffffffff');
			$x = 1; $y = 0;
			while($x <= $noofcust){
				
			//	echo "\n X :  ".$x;
				
				$new_rd_model =  new Redistribution();
				$new_dmodel = self::findCDModel($custlistids[$y]);
				$new_rd_model->agent_id = $redis_agent_id;
				$new_rd_model->customer_id = $new_dmodel->customer_id;
				$new_rd_model->distributed_by = Yii::$app->user->identity->id;
				$new_rd_model->save();
			
				$new_dmodel->agent_id = $redis_agent_id;
				//$new_dmodel->customer_id = $customer;
				$new_dmodel->distributed_by = Yii::$app->user->identity->id;
				$new_dmodel->campaign_id = $campaignid;
				$new_dmodel->no_of_customers = $noofcust;
				$new_dmodel->save();
				
			/*	echo "redistribution model\n";
				var_dump($new_rd_model);
				echo "distribution model\n";
				var_dump($new_dmodel); */
				$x++; $y++;
			}
			
			/*echo Alert::widget([
					'options' => [
						'class' => 'alert-info',
					],
					'body' => 'Redistribution done...',
				]);
			*/
			$model = new ContactDistribution();
            $rd_model = new Redistribution();
			
			return $this->redirect(['/myinbox']);
			return $this->render('create', [
            'rd_model' => $rd_model,
			'model'=> $model,
			'noofcustomers'=>0,
			]);
        }
		else{
			
			
		//die('entered else');
			return $this->render('create', [
		'customerDataProvider'=>$customerDataProvider,
            'rd_model' => $rd_model,
			'model'=> $model,
			'noofcustomers'=>0,
			]);
		}
    }

    /**
     * Updates an existing Redistribution model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
*/
    /**
     * Deletes an existing Redistribution model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
*/
    /**
     * Finds the Redistribution model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Redistribution the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Redistribution::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	protected function findCDModel($id)
    {
        if (($model = ContactDistribution::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
