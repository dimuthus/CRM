<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'prod');


require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);


$application = new yii\web\Application($config);
$application->run();
// B0Ccrm@dm1N!@#$%

// /getcustomerybymrn?mrn=40396E5D3B7327016F16DC131C16F4C3&jmc=953C967B5BFD66AEC6403DE829C02C44
// /getcustomerybymrn?mrn=953C967B5BFD66AEC6403DE829C02C44&jmc=953C967B5BFD66AEC6403DE829C02C44