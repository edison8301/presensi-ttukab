<?php

namespace app\controllers;

use app\models\Instansi;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\FilterForm;

class AdminController extends \yii\web\Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','index'],
                'rules' => [
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $model = new FilterForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setSession();
        }

        $model->tahun = Yii::$app->session->get('tahun',date('Y'));

        $allOpd = Instansi::find()->all();

        return $this->render('index',['model'=>$model,'allOpd'=>$allOpd]);
    }

}
