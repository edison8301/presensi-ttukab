<?php

namespace app\controllers;

use app\models\FilterForm;
use Yii;

class FilterController extends \yii\web\Controller
{
    public $defaultAction = 'filter';
    
    public function actionFilter()
    {
        $model = new FilterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setSession();
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

}
