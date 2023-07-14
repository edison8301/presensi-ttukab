<?php

namespace app\modules\kinerja\controllers;

use Yii;
use yii\web\Controller;
use app\models\Instansi;
use app\models\InstansiSearch;

class InstansiController extends Controller
{
    public function actionIndex()
    {
    	$searchModel = new InstansiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Instansi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
