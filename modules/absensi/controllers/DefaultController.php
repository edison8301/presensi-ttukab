<?php

namespace app\modules\absensi\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
    	return $this->render('index');
    }

    public function actionUser($id)
    {
    	$user = \app\modules\kinerja\models\User::findOne($id);
    	return $this->render('user',['user'=>$user]);
    }
}

?>