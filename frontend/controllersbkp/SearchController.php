<?php

namespace frontend\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\data\ActiveDataProvider;
    use yii\web\NotFoundHttpException;
    use yii\web\ForbiddenHttpException;
    use yii\filters\VerbFilter;
    use yii\web\Response;
    use yii\filters\AccessControl;
    
    use frontend\models\request\Request;
    use frontend\models\customer\Customer;
    use frontend\models\customer\CustomerSearch;
    use frontend\models\cases\CustomerCases;
    use frontend\models\jmccrypt\JmcCrypt;

class SearchController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'search', 'loadcustomercase'],
                'rules' => [
                    [
                       'actions' => ['index'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Search Page'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ],

                    [
                       'actions' => ['search','loadcustomercase'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Search Contacts'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return Yii::$app->request->isAjax;
                       }
                    ],
               ],
            ],
        ];
    }



    public function actionIndex()
    {

	//die('TEST');
        $searchModel = new CustomerSearch();

        $results = new ActiveDataProvider([
            'query' => Customer::find()->where(['id' => -1])
        ]);

        $query =

		CustomerCases::find()->where('customer_cases.case_status NOT IN (2,4)')
            ->joinWith('customer')
            ->orderby('created_datetime DESC');

        if(!Yii::$app->user->can('View Service Reuqests Created by All Agents'))
            $query->andFilterWhere(['customer_cases.created_by' => Yii::$app->user->identity->id]);


    	$cases= new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                    'pageSize' => 10,
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'cases' => $cases,
            'results' => $results ,
        ]);
    }

    public function actionSearch()
    {
		//die('SearchHere');
        $params =Yii::$app->request->queryParams;
       
        
        $createFromSearch = Yii::$app->request->getQueryParam('createFromSearch');

        if(!empty($createFromSearch)) {
            Yii::$app->session['search_values'] = ['Customer'=>$params['CustomerSearch']];
            return $this->redirect(['customer/create']);
        }

        $searchModel = new CustomerSearch();
        


           // $params['CustomerSearch']['full_name']=$searchModel->encryptString($params['CustomerSearch']['full_name']); 
           // $params['CustomerSearch']['mobile_number']=$searchModel->encryptString($params['CustomerSearch']['mobile_number']);

           

            $results = $searchModel->search($params);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $body = $this->renderAjax('search_results', ['results' => $results]);
        $found = $results->getTotalCount()>0?true:false;

        return [
            'body' => $body,
            'found' => $found,
        ];
    }

    public function actionLoadcustomercase($id)
    {
        if(!empty($id)) {
          
           $case= $this->findCase($id);
           $jmcIns = new JmcCrypt();
           $hashID= $jmcIns->HashMe($case->customer_id);


            Yii::$app->session['case_id_to_load'] = $id;
            return $this->redirect(['customer/'.$case->customer_id.'?jmc='.$hashID]);

        }
    }

    public function actionLoadcustomercaseout($id)
    {
        
        if(!empty($id)) {
            
          $jmcIns = new JmcCrypt();
          $hashID= $jmcIns->HashMe($id);
          $outbnd='100';
          $campId=$_GET['campId'];
          //echo ('customer/'.$id.'?outbnd='.$outbnd.'&jmc='.$hashID);
            //$case= $this->findCase($id);
            
            Yii::$app->session['case_id_to_load'] = $id;
            
            return $this->redirect(['customer/'.$id.'?outbnd='.$outbnd.'&jmc='.$hashID.'&campId='.$campId]);

        }
    }
    protected function findCase($id)
    {
        if (($model =CustomerCases::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }




}
