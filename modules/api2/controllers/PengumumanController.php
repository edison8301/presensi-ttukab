<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04/02/2020
 * Time: 06.48
 */

namespace app\modules\api2\controllers;

use yii\web\Controller;
use app\modules\api2\models\Pengumuman;
use Yii;

class PengumumanController extends Controller
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

    public function actionIndex($nip=null)
    {
        $limit = Yii::$app->request->get('limit');

        $data = Pengumuman::restApiIndex([
            'nip' => $nip,
            'limit' => $limit,
        ]);

        return $data;
    }
}
