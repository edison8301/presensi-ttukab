<?php


namespace app\modules\api\controllers;

use app\models\Pegawai;
use Yii;
use yii\web\Controller;

class DataUtamaController extends Controller
{
    public $enableCsrfValidation = false;
    public $attribute;

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

    public function actionIndex($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);

        $instansiPegawai = $model->instansiPegawaiBerlaku;
        $list = '';

        $list = [
            'id' => @$model->id,
            'nama' => @$model->nama,
            'nip' => @$model->nip,
            'instansi' => @$model->instansi->nama,
            'atasan' => @$instansiPegawai->atasan->nama,
            'nama_golongan' => @$model->nama_golongan,
            'nama_jabatan' => @$model->nama_jabatan,
            'jenis_kelamin' => @$model->gender,
            'tempat_lahir' => @$model->tempat_lahir,
            'tanggal_lahir' => @$model->tanggal_lahir,
            'alamat' => @$model->alamat,
            'telepon' => @$model->telepon,
            'email' => @$model->email,
            'gelar_depan' => @$model->gelar_depan,
            'gelar_belakang' => @$model->gelar_belakang,
            'eselon' => @$model->eselon->nama,
            'tmt_golongan' => @$model->tmt_golongan,
            'tmt_jabatan' => @$model->tmt_jabatan,
            'pendidikan_terakhir' => @$model->pendidikan_terakhir,
            'nama_kampus' => @$model->nama_kampus,
            'gelar_depan_atasan' => @$instansiPegawai->atasan->nama,
            'gelar_belakang_atasan' => @$instansiPegawai->atasan->nama,
            'jabatan_atasan' => @$instansiPegawai->jabatanAtasan->nama,
            'nip_atasan' => @$instansiPegawai->atasan->nip,
        ];

        return $list;
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request->post();
        $model = Pegawai::findOne($id);

        foreach ($request as $attribute => $value) {
            $model->$attribute = $value; 
        }
        $model->update();

        return [
            'success' => true,
            'message' => 'Data berhasil diubah'
        ];
    }
}
