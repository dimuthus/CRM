<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

use frontend\models\myinbox\Myinbox;

class MyinboxController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'search'],
                'rules' => [
                    [
                       'actions' => ['index'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Dashboard Page'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }

                    ],
                    [
                       'actions' => ['search'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Yii::$app->request->isAjax;
                       }
                    ]
               ],
            ]
        ];
    }

    public function actionIndex()
    {

        $searchModel = new Myinbox();
$encryptionKey = Yii::$app->params['encryptionKey'];
        $select = [' AES_DECRYPT(UNHEX(cust.`full_name`), "'.$encryptionKey.'") AS full_name','(CASE  WHEN cust.`new_nic` = ""  THEN cust.passport  ELSE cust.new_nic  END) AS identity',
        'cust.gender','AES_DECRYPT(UNHEX(cust.`email`), "'.$encryptionKey.'") AS email','u.username AS username','cdg.campaign_id','c.name','cust.id'];

        $query = new Query;
        if ((Yii::$app->user->identity->id)!=1) { // for the admin user having all data.
        $query->select($select)
            ->from('customer cust')
          //  ->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
            ->join('INNER JOIN', 'contact_distribution cdg','cdg.customer_id = cust.id')
            ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id')
            ->join('INNER JOIN', 'user u','u.id = cdg.agent_id')
            ->Where(['cdg.agent_id' => Yii::$app->user->identity->id]);
          } else {
            $query->select($select)
                ->from('customer cust')
                //->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
                ->join('INNER JOIN', 'contact_distribution cdg','cdg.customer_id = cust.id')
                ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id')
                ->join('INNER JOIN', 'user u','u.id = cdg.agent_id');

          }

        $sort = [
            'defaultOrder' => ['full_name'=>SORT_ASC],
            'attributes' => [
                'full_name',
                'total' => [
                    'asc' => ['total' => SORT_ASC],
                    'desc' => ['total' => SORT_DESC],
                    'default' => SORT_DESC

                ]

            ]
        ];
 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort
        ]);


        $querySummary = new Query;

  if ((Yii::$app->user->identity->id)!=1) { // for the admin user having all data.
          $querySummary->select($select)
            ->from('user u')
            ->join('LEFT OUTER JOIN', 'contact_distribution cdg','cdg.agent_id =u.id')
            ->join('INNER JOIN', 'customer cust','cust.id = cdg.customer_id')
            //->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
            ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id')

              ->Where(['cdg.agent_id' => Yii::$app->user->identity->id]);
            } else {

            $querySummary->select($select)
                  ->from('user u')
                  ->join('LEFT OUTER JOIN', 'contact_distribution cdg','cdg.agent_id =u.id')
                  ->join('INNER JOIN', 'customer cust','cust.id = cdg.customer_id')
                  //->join('INNER JOIN', 'customer_campaign cc','cc.customer_id = cust.id')
                  ->join('INNER JOIN', 'campaign c','c.id = cdg.campaign_id');

            }

            //echo $query->createCommand()->getRawSql();

        $summary = $querySummary->all();
        if ($summary!=NULL){
            $capSummary = $summary[0];
        } else {

           $capSummary="{0:1}"; //no record found for an agent.
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'summary' => $capSummary
        ]);
    }

    public function actionSearch()
    {
        $params =Yii::$app->request->queryParams;

        $searchModel = new Myinbox();
        $results = $searchModel->search($params);

        return $this->renderAjax('myinbox', [
            'dataProvider' => $results['dataProvider'],
            'summary' => $results['summary'],
        ]);

    }

}
