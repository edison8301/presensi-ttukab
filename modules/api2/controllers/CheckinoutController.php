<?php

namespace app\modules\api2\controllers;

use Yii;
use app\models\Pegawai;
use app\modules\api2\models\Checkinout;
use app\modules\iclock\models\Userinfo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Default controller for the `api1` module
 */
class CheckinoutController extends Controller
{
    public $enableCsrfValidation = false;

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
    public function actionIndex($nip = null)
    {
        $userinfo = Userinfo::findOne(['badgenumber' => $nip]);

        $query = Checkinout::find();
        $query->orderBy(['checktime' => SORT_DESC]);
        $query->andWhere([
            'userid' => @$userinfo->userid
        ]);

        $query->limit(30);

        return $query->all();
    }

    public function actionCreate()
    {

        $this->layout = '';
        $post['Checkinout'] = Yii::$app->request->post();

        $model = new Checkinout();
        $model->load($post);

        $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $model->userid = $model->getUserIdByUsername();
        $model->checktime = $datetime->format('Y-m-d H:i:s');
        $model->checktype = '1';
        $model->verifycode = '1';
        $model->id_checkinout_sumber = '2'; // sumber dari mobile
        $model->foto = $datetime->format('YmdHis') . '_' . $model->getNipByUsername() . '.jpg';

        $model->setLokasiAbsen();
        $model->setStatusLokasiKantorBanyak();

        if($model->status_lokasi_kantor != 1) {
            return [
                'success' => false,
                'message' => 'Rekam kehadiran gagal. Anda berada di luar lokasi. Silahkan masuk ke radius titik lokasi kantor'
            ];
        }

        if ($model->save()) {
            $foto_decode = base64_decode(Yii::$app->request->post('foto_encode'));
            $path = Yii::$app->basePath . '/web/uploads/checkinout/';
            file_put_contents($path . $model->foto, $foto_decode);
            //$model->resizeImage();
        } else {
            return [
                'success' => false,
                'message' => $model->getErrors()
            ];
        }

        $message = 'Rekam Kehadiran Berhasil Dilakukan. Posisi Absen: ';

        if ($model->status_lokasi_kantor == 1 OR $model->status_lokasi_kantor == 2) {
            $message .= $model->getNamaStatusLokasiKantor();
        } else {
            $message .= 'N/A (' . $model->getNamaStatusLokasiKantor() . ')';
        }

        return [
            'success' => true,
            'message' => $message
        ];
    }

    public function actionView($id)
    {
        $model = Checkinout::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('Data tidak ditemuukan');
        }

        return $model;
    }
}
