<?php

namespace app\modules\tukin\controllers;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class FileController
 * Untuk mengambil file di dalam protected.
 * @package app\modules\tukin\controllers
 */
class FileController extends \yii\web\Controller
{
    public $defaultAction = 'get';

    /**
     * @param null $fileName
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionGet($fileName = null)
    {
        $storagePath = Yii::getAlias('@app/files');
        if (!is_file("$storagePath/$fileName")) {
            throw new NotFoundHttpException('File tidak ditemukan.');
        }
        return Yii::$app->response->sendFile("$storagePath/$fileName", $fileName);
    }
}
