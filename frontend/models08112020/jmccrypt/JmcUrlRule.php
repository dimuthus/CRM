<?php

namespace frontend\models\jmccrypt;

use Yii;
use yii\BaseYii;
use yii\web\UrlManager;

/**
 * Encryption and Decryption class
 */
class JmcUrlRule extends CBaseUrlRule
{
    public function createUrl($manager,$route,$params,$ampersand)
    {
        if ($route==='core/student/create')
        {
            // here use your own encryption logic
            return base64_encode($route);
        }
        return false;  // this rule does not apply
    }

    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        // here use your own decryption logic
        $decoded = base64_decode($pathInfo);
        if ($decoded==='core/student/create') {
            return $decoded;
        }
        return false;  // this rule does not apply
    }
}
