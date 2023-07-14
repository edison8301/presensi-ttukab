<?php


namespace app\modules\api\controllers;


use app\modules\api\models\UploadPresensiRest;
use Yii;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class CreateAction extends \yii\rest\CreateAction
{
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, Yii::$app->getRequest()->getBodyParams());
        }
        /*if (Yii::$app->getRequest()->getBodyParam('token') !== 'Kt9LqQUDkRoWtXO2o-FeWUkPGYIMcw6-') {
            throw new ForbiddenHttpException('test lorem');
        }*/

        /* @var $model UploadPresensiRest */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->fileInstance = UploadedFile::getInstanceByName('fileInstance');
        if ($model->fileInstance instanceof UploadedFile) {
            $model->file = 'remote_' . $model->fileInstance->name;
        } else {
            throw new ServerErrorHttpException('File tidak ditemukan');
        }
        if ($model->upload() && $model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }
}
