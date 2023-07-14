<?php

namespace app\modules\api\controllers;

use app\modules\iclock\models\Checkinout;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CheckinoutController extends Controller
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

    public function actionCreate($userid=null, $checktime=null, $checktype=0,
         $verifycode=null, $SN=null, $sensorid=null, $WorkCode=0,
         $Reserved=null, $token=null, $debug=false
    ) {

        if($userid==null) {
            throw new ForbiddenHttpException();
        }

        if($checktime==null) {
            throw new ForbiddenHttpException();
        }

        if($verifycode==null) {
            throw new ForbiddenHttpException();
        }

        if($SN==null) {
            throw new ForbiddenHttpException();
        }

        if($sensorid==null) {
            throw new ForbiddenHttpException();
        }

        if($Reserved==null) {
            throw new ForbiddenHttpException();
        }

        if($token==null) {
            throw new ForbiddenHttpException();
        }

        if($token!='DGEsdge425234SRfewrASDAFw3rSDFwer') {
            throw new ForbiddenHttpException();
        }

        $model = new Checkinout();
        $model->userid = $userid;
        $model->checktime = $checktime;
        $model->checktype = $checktype;
        $model->verifycode = $verifycode;
        $model->SN = $SN;
        $model->sensorid = $sensorid;
        $model->WorkCode = $WorkCode;
        $model->Reserved = $Reserved;
        $model->status_kirim = Checkinout::STATUS_KIRIM_TERIMA;
        $model->waktu_kirim = date('Y-m-d H:i:s');

        if($model->save() != true) {
            return $model->getErrors();
        }

        return "true";
    }
}
