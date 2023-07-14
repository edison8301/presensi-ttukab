<?php

namespace app\modules\api2\controllers;

use yii\web\Controller;

/**
 * Default controller for the `api2` module
 */
class DefaultController extends Controller
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
        return 'Hello World';
    }
}
