<?php
/**
 * Yii quiz
 *
 * @author Marc Oliveras Galvez <oligalma@gmail.com>
 * @link http://www.oligalma.com
 * @copyright 2016 Oligalma
 * @license GPL License
 */

namespace frontend\modules\survey;

use Yii;

class Survey extends \yii\base\Module{
    public $controllerNamespace = 'frontend\modules\survey\controllers';
    private $_assetsUrl;

    public function init()
    {
	parent::init();
    }

}
