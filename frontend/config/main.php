<?php
use yii\rest\UrlRule;
use yii\web\JsonParser;
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'BOC CRMS',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],

    'controllerNamespace' => 'frontend\controllers',

    'components' => [

	    'request' => [
            'baseUrl' => '/boc_crm',
			'parsers' => [
                'application/json' => JsonParser::class
            ]
        ],


        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/boc_crm', //for local /crm-bat
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' =>[
                'tools/<controller:\w+>/<id:\d+>' => 'tools/<controller>/view',
                'tools/<controller:\w+>/<action:\w+>/<id:\d+>' => 'tools/<controller>/<action>',
                'tools/<controller:\w+>/<action:\w+>' => 'tools/<controller>/<action>',
               // 'api/<controller:\w+>/<action:\w+>' => 'api/<controller>/<action>',
				'survey/<controller:\w+>/<id:\d+>' => 'survey/<controller>/view',
                'survey/<controller:\w+>/<action:\w+>/<id:\d+>' => 'survey/<controller>/<action>',
                'survey/<controller:\w+>/<action:\w+>' => 'survey/<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				['class' => UrlRule::class, 
						  'controller'    => 'customer',
                'pluralize'     => false,
						 'extraPatterns' => [
							'GET vip' => 'customer/vip',
							'GET language' => 'customer/language',
							'GET mobile' => 'customer/mobile',
							'GET tpinregister' => 'customer/tpinregister',
							'GET tpinvalidate' => 'customer/tpinvalidate',
						],
				],
               
            ],
        ],

		'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
		'session'=>[
            'class' => 'yii\web\Session',
            'timeout' =>600, //


        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 600, // need to login after 10 minutes
			'loginUrl' => ['user/login'],

        ],
      

		'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*', // add or remove allowed actions to this list
			'customer/*', // add or remove allowed actions to this list
        ]
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

    ],
    'modules' => [
        'tools' => [
            'class' => 'frontend\modules\tools\Tools',
        ],
              'survey' => [
            'class' => 'frontend\modules\survey\Survey',
        ],
          'api' => [

            'class' => 'frontend\modules\api\Api',

        ],
    ],
    
    // Below comment is done by Dimuthu on 13/05/2020 for the purpose of api access, when uncomment this it will redirect to login page
//    'as beforeRequest' => [
//        'class' => 'yii\filters\AccessControl',
//        'rules' => [
//            [
//                'actions' => ['login', 'error'],
//                'allow' => true,
//            ],
//            [
//
//                'allow' => true,
//                'roles' => ['@'],
//            ],
//        ],
//    ],


     'on beforeRequest' =>function () {
        // if (!Yii::$app->get('user', false)) {
        //     return;
        // }

        // $user = User::getCurrent();
        // if ($user) {
        //     Yii::$app->setTimeZone($user->time_zone);
        // }


         if (strpos(Yii::$app->request->url, 'api') == true){

            return true;
        }

        if (strpos(Yii::$app->request->url, 'changepassword') == true){

            return false;
        }
        if(Yii::$app->user->isGuest){
        return false;
       } else if(!Yii::$app->user->isGuest){

            


         function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }




                               if(dateDifference(date('Y-m-d h:i:s'),Yii::$app->user->identity->passwordtupdate_datetime)>89 || Yii::$app->user->identity->passwordtupdate_datetime==NULL)
                    {   

                                                                    if (strpos(Yii::$app->request->url, 'changepassword') !== true) {
                            return  Yii::$app->response->redirect(Yii::$app->homeUrl."user/changepassword");
}


                        
                    }  
                    if(Yii::$app->user->identity->firsttime==0){
                                                                                           if (strpos(Yii::$app->request->url, 'changepassword') !== true) {
                              return  Yii::$app->response->redirect(Yii::$app->homeUrl."user/changepassword");
}
                    }






       }

        
    } ,

    'params' => $params,

];
