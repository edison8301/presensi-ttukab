<?php

namespace app\modules\api\controllers;

use app\modules\api\models\UploadPresensiRest;
use Yii;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `api` module
 */
class UploadPresensiRestController extends ActiveController
{
    public $modelClass = UploadPresensiRest::class;

    public function checkAccess($action, $model = null, $params = [])
    {
        $token = Yii::$app->getRequest()->getBodyParam('token');
        if ($token !== Yii::$app->params['token']) {
            throw new ForbiddenHttpException('Error 403');
        }
    }

    public function actions()
    {
        $actions = parent::actions();
        return array_merge(
            $actions,
            [
                'create' => [
                    'class' => CreateAction::class,
                    'modelClass' => $this->modelClass,
                    'checkAccess' => [$this, 'checkAccess'],
                    'scenario' => $this->createScenario,
                ]
            ]
        );
    }

}
