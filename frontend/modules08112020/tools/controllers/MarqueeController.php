<?php

namespace frontend\modules\tools\controllers;

use Yii;
use frontend\modules\tools\models\user\User;
use frontend\models\customer\CustomerContactType;
use frontend\models\customer\CustomerTitle;
use frontend\models\customer\CustomerLanguage;
use frontend\models\request\RequestStatus;
use frontend\models\request\RequestPriority;
use frontend\models\interaction\InteractionChannelType;
use frontend\models\interaction\InteractionCurrentOutcome;
use frontend\models\Country;
use frontend\models\Marquee;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Logadm;

/**
 * UserController implements the CRUD actions for User model.
 */
class MarqueeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update'],
                'rules' => [
                    [
                       'actions' => ['index'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                            if(!Yii::$app->user->can('Marquee Management Module'))
                                throw new ForbiddenHttpException(Yii::$app->params['authorizationError']);
                            return true;
                        }
                    ],
                    [
                       'actions' => ['update'],
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           return Yii::$app->request->isAjax && Yii::$app->request->isPost;
                       }
                    ]
                ],
                
        ],
        ];
    }

    
    public function actionIndex() {

        $model = Marquee::findOne(1);

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionUpdate() {

        $model = Marquee::findOne(1);

        $oldName=$model->message;
        $newName=Yii::$app->request->post("Marquee")['message'];
        $hasError = true;
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
               $logmodel= new Logadm();              
        $logmodel->createUpdateLogRule("Marquee",$oldName,$newName);
                $hasError = false;
            }
        } 

        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['hasError'=>$hasError];
    }

}
