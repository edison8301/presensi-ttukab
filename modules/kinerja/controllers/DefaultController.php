<?php

namespace app\modules\kinerja\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
    	return $this->render('index');
    }
}
