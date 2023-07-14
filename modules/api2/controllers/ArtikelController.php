<?php

namespace app\modules\api2\controllers;

use app\modules\api2\models\Artikel;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `api2` module
 */
class ArtikelController extends Controller
{
    public function behaviors()
    {
       return [
           [
               'class' => \yii\filters\ContentNegotiator::class,
               'formats' => [
                   'application/json' => \yii\web\Response::FORMAT_JSON,
               ],
           ],
       ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        $limit = Yii::$app->request->get('limit');
        
        $data = Artikel::restApiIndex([
            'limit' => $limit,
        ]);

        return $data;
    }

    public function actionView($id)
    {
        $model = Artikel::findOne($id);

        if($model===null) {
            throw new NotFoundHttpException('Data tidak ditemuukan');
        }

        return $model->restJson();
    }

//    public function actionView($id)
//    {
//        $model = Artikel::findOne([
//           'id' => $id
//        ]);
//        return $model;
//    }


}
