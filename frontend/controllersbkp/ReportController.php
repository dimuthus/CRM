<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\PHPExcel;

use frontend\models\report\ReportSearch;
use frontend\models\request\Request;
use frontend\models\cases\CustomerCases;
use frontend\models\report\CsatSurvey;
use frontend\models\report\InboundInteraction;
use frontend\models\report\OutboundInteraction;

class ReportController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['search', 'complete', 'summary','outbound','csat'],
                'rules' => [
                    [
                       'actions' => ['complete','outbound'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Reporting Page (Inbound)'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }

                    ],
                    [
                       'actions' => ['summary','summary-outbound'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Reporting Page (Outbound)'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }

                    ],
				    [
                       'actions' => ['csat'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Reporting Page (Csat)'))
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

    public function actionComplete()
    {
        $searchModel = new ReportSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => CustomerCases::find()->where(['id' => -1])
        ]);

        return $this->render('complete', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionGenerateComplete()
    {
 		$this->logActivity('Inbound Report');

        $params =Yii::$app->request->queryParams;
		$searchModel = new ReportSearch();
        $results = $searchModel->generateComplete($params);
        return $this->renderAjax('complete_result', [ 'dataProvider' => $results, 'pagination' => false ]);

    }

	public function actionOutbound()
    {
        $searchModel = new ReportSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => CustomerCases::find()->where(['id' => -1])
        ]);

        return $this->render('outbound', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionGenerateOutbound()
    {
		$this->logActivity('Outbound Report');
        $params =Yii::$app->request->queryParams;
		$searchModel = new ReportSearch();
        $results = $searchModel->generateOutbound($params);
        return $this->renderAjax('outbound_result', [ 'dataProvider' => $results, 'pagination' => false ]);

    }
   

    public function actionSummary()
    {
        $searchModel = new ReportSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => CustomerCases::find()->where(['id' => -1])
        ]);
        return $this->render('summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
 public function actionGenerateSummary()
    {
        ini_set('max_execution_time', 300); 
        ini_set('memory_limit', '1024M');
        $params =Yii::$app->request->queryParams;

        $searchModel = new ReportSearch();
        $results = $searchModel->generateSummaryInbound($params);

        return $this->renderAjax('summary_result', [
            'dataProvider' => $results,
        ]);
    }
	
	  public function actionSummaryOutbound()
    {
        $searchModel = new ReportSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => CustomerCases::find()->where(['id' => -1])
        ]);
        return $this->render('summary_outbound', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
 public function actionGenerateSummaryOutbound()
    {
        ini_set('max_execution_time', 300); 
        ini_set('memory_limit', '1024M');
        $params =Yii::$app->request->queryParams;

        $searchModel = new ReportSearch();
        $results = $searchModel->generateSummaryOutbound($params);

        return $this->renderAjax('summary_result_outbound', [
            'dataProvider' => $results,
        ]);
    }
	
	public function actionCsat()
    {
        $searchModel = new ReportSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => CsatSurvey::find()->where(['id' => -1])->groupBy('respondent_id')
        ]);

        return $this->render('csat', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionGenerateCsat()
    {
		 		$this->logActivity('Csat Report');

        $params =Yii::$app->request->queryParams;

        $searchModel = new ReportSearch();
        $results = $searchModel->generateCsat($params);

        return $this->renderAjax('csat_result', [
            'dataProvider' => $results,
        ]);

    }
	
	public function actionActivitylog()
    {
        $searchModel = new ReportSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => CsatSurvey::find()->where(['id' => -1])->groupBy('respondent_id')
        ]);

        return $this->render('activity', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    
	public function actionGenerateActivitylog()
    {
        $params =Yii::$app->request->queryParams;

        $searchModel = new ReportSearch();
        $results = $searchModel->generateActivitylog($params);

        return $this->renderAjax('activity_result', [
            'dataProvider' => $results,
        ]);

    }
	
	public function logActivity($moduleName){
		$ip=Yii::$app->getRequest()->getUserIP();
		$dml_created_by=Yii::$app->user->identity->id;
	  \Yii::$app->db->createCommand("INSERT INTO audit_log (module_name,dml_timestamp,dml_created_by,ip_address) VALUES ('$moduleName',NOW(),$dml_created_by,'$ip')")->execute();
		
	}

}
