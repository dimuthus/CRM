<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

use frontend\models\dashboard\DashboardSearch;

class DashboardController extends Controller
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
        
        $searchModel = new DashboardSearch();

        $select = '
            u.username AS username,
                COUNT(r.id) AS total,
                COUNT(CASE WHEN
                    r.case_status = 2 
                    THEN 1 ELSE NULL END) AS closed,
                COUNT(CASE WHEN
                    r.case_status = 1 or
                    r.case_status = 3 or
                    r.case_status = 4
                    THEN 1 ELSE NULL END) AS `open`,
                COUNT(CASE WHEN
                    r.case_status = 5
                    
                    THEN 1 ELSE NULL END) AS escalated,
               DATEDIFF(NOW(), MIN(CASE WHEN
                r.case_status != 2
                THEN r.created_datetime ELSE NOW() END)) AS aging

        ';

        $query = new Query;

        $query->select($select)
            ->from('user u')
            ->join('LEFT OUTER JOIN', 'customer_cases r','r.created_by =u.id')
            ->where(['!=','u.role_id','Admin'])
            ->groupby('username');

        $sort = [
            'defaultOrder' => ['username'=>SORT_ASC],
            'attributes' => [
                'username',
                'total' => [
                    'asc' => ['total' => SORT_ASC],
                    'desc' => ['total' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'closed' => [
                    'asc' => ['closed' => SORT_ASC],
                    'desc' => ['closed' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'open' => [
                    'asc' => ['open' => SORT_ASC],
                    'desc' => ['open' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'escalated' => [
                    'asc' => ['escalated' => SORT_ASC],
                    'desc' => ['escalated' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'aging' => [
                    'asc' => ['aging' => SORT_ASC],
                    'desc' => ['aging' => SORT_DESC],
                    'default' => SORT_DESC
                ],
            ]
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort
        ]);


        $querySummary = new Query;

        $querySummary->select($select)
            ->from('user u')
            ->join('LEFT OUTER JOIN', 'customer_cases r','r.created_by =u.id')
            ->where('u.id != 1');

        $summary = $querySummary->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'summary' => $summary[0]
        ]);
    }

    public function actionSearch()
    {
        $params =Yii::$app->request->queryParams;

        $searchModel = new DashboardSearch();
        $results = $searchModel->search($params);

        return $this->renderAjax('dashboard', [
            'dataProvider' => $results['dataProvider'],
            'summary' => $results['summary'],
        ]);

    }

}
