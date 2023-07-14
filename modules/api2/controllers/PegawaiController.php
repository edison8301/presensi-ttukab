<?php

namespace app\modules\api2\controllers;

use app\modules\api2\models\Pegawai;
use yii\web\Controller;
use app\models\InstansiPegawai;
use app\models\PegawaiStatus;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `api1` module
 */
class PegawaiController extends Controller
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

    public function actionUpdatePhoto(){
        $post['Pegawai'] = Yii::$app->request->post();

        $data = new Pegawai();
        $data->load($post);

        $model = Pegawai::findOne([
            'nip' => $data->nip,
        ]);

        $fotoLama = $model->foto;

        $model->foto = $data->foto;

        if($model->save()) {
            $foto_decode = base64_decode(Yii::$app->request->post('foto_encode'));
            $path = Yii::$app->basePath.'/web/uploads/foto/';
            if(!is_dir($path)){
                //Directory does not exist, so lets create it.
                mkdir($path, 0777, true);
            }
            if(file_exists($path.$fotoLama) != false and $fotoLama != null) {
                unlink($path.$fotoLama);
            }
            file_put_contents($path.$model->foto,$foto_decode);
            return [
                'status' => true,
                'message' => 'Foto berhasil diupdate',
            ];
        }else{
            return [
                'status' => false,
                'message' => $model->errors,
            ];
        }
    }

    public function actionIndexPegawaiBawahan($nip = null, $tahun=null)
    {
        $pegawai = Pegawai::findOne(['nip'=>$nip]);

        $tahun = $tahun ?? date('Y');
        $bulan = date('m');
        $hari = date('d');

        $tanggal = $tahun.'-'.$bulan.'-'.$hari;

        $query = InstansiPegawai::find();
        $query->joinWith(['jabatan']);
        $query->andWhere('tanggal_mulai <= :tanggal', [':tanggal' => $tanggal]);
        $query->andWhere('tanggal_selesai >= :tanggal', [':tanggal' => $tanggal]);
        $query->andWhere(['!=','jabatan.nama', '']);
        $query->andWhere(['jabatan.id_induk' => @$pegawai->instansiPegawai->jabatan->id]);

        $json = [];

        foreach ($query->all() as $data) {
            array_push($json, [
                'id' => $data->id,
                'id_pegawai' => $data->id_pegawai,
                'nama' => @$data->pegawai->nama,
                'nip' => @$data->pegawai->nip,
                'id_instansi' => $data->id_instansi
            ]);
        }

        return $json;
    }

    public function actionView($nip)
    {
        $model = Pegawai::findOne([
           'nip'=>$nip
        ]);

        return $model;
    }

    public function actionCheckStatus($nip)
    {
        $model = $this->findPegawaiByNip(['nip' => $nip]);

        return [
            'status' => $model->id_pegawai_status == PegawaiStatus::AKTIF,
        ];
    }

    protected function findPegawaiByNip($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
