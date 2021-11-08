<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/loading.css',
        'css/password_strength.css',
		'css/jquery.growl.css',
        'css/jquery.dataTables.min.css',
        'css/buttons.dataTables.min.css',
    ];
    public $js = [
/*        'js/jClocksGMT.js',
        'js/jquery.MyDigitClock.js',*/
      	//'js/jquery-3.3.1.js',
		'js/jquery.sticky.js',
        'js/main.js',
        'js/loading.js',
        'js/password_strength_lightweight.js',
       // 'js/jquery.table2excel.js',
       'js/jquery.dataTables.min.js',
        'js/dataTables.buttons.min.js',
         'js/buttons.print.min.js',
		 'js/buttons.html5.min.js',
         'js/buttons.flash.min.js',

         'js/jszip.min.js',
		 
		 
		 
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
