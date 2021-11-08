<?php

namespace frontend\modules\survey\controllers;

class MyTestController extends \yii\web\Controller
{
    public function actionAbc()
    {
        return $this->render('abc');
    }

    public function actionPqr()
    {
        return $this->render('pqr');
    }

    public function actionXyz()
    {
        return $this->render('xyz');
    }

}
