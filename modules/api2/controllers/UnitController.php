<?php

namespace app\modules\api2\controllers;

use app\modules\api2\models\Kak;
use app\modules\api2\models\Unit;
use yii\web\Controller;

/**
 * Default controller for the `api2` module
 */
class UnitController extends Controller
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
            'corsFilter'  => [
                'class' => \yii\filters\Cors::class,
                'cors'  => [
                    'Origin' => ['http://localhost:3000'],
                    'Access-Control-Request-Method' => ['GET'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,

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
        $query = Unit::find();
        $query->andWhere(['id_unit_eselon'=>[1,2]]);
        return $query->all();
    }
}
