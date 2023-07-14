<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 19/05/2019
 * Time: 06.14
 */

namespace app\modules\api2\controllers;


use app\models\InstansiSearch;
use app\modules\api2\models\Instansi;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

class InstansiController extends ActiveController
{
    public $modelClass = '\app\modules\api2\models\Instansi';

    public function actionIndexAll()
    {
        $searchModel = new InstansiSearch();
        $query = $searchModel->getQuerySearch(Yii::$app->request->queryParams);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
    }
}
