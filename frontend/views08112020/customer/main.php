<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
require(__DIR__ . '/../sftpconnect/Net/SFTP.php');
    require(__DIR__ . '/../sftpconnect/Math/BigInteger.php');
        require(__DIR__ . '/../sftpconnect/Crypt/Random.php');
        require(__DIR__ . '/../sftpconnect/Crypt/Base.php');
        require(__DIR__ . '/../sftpconnect/Crypt/Hash.php');
        require(__DIR__ . '/../sftpconnect/Crypt/RC4.php');
return [
    'id' => 'app-frontend',
    'name' => 'SRILANKAN AIRLINES CRMS',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
	
	    'request' => [
            'baseUrl' => '/sla_crms',
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            //'baseUrl' => '/sla_crms',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => array(
                'tools/<controller:\w+>/<id:\d+>' => 'tools/<controller>/view',
                'tools/<controller:\w+>/<action:\w+>/<id:\d+>' => 'tools/<controller>/<action>',
                'tools/<controller:\w+>/<action:\w+>' => 'tools/<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],

		'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
		
		
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
    ],
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login', 'error'],
                'allow' => true,
            ],
            [

                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => $params,
];
