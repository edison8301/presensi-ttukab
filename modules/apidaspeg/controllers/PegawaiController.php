<?php


namespace app\modules\apidaspeg\controllers;

use app\models\Eselon;
use app\models\Golongan;
use app\models\Pegawai;
use app\models\Pendidikan;
use Yii;
use yii\web\Controller;

class PegawaiController extends Controller
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

    public function actionView($nip)
    {
        $model = Pegawai::findOne(['nip' => $nip]);

        $list = '';

        $tmtGolAkhir = null;
        $datetime = \DateTime::createFromFormat('Y-m-d', $model->tmt_golongan);
        if($datetime != false) {
            $tmtGolAkhir = $datetime->format('d-m-Y');
        }

        $tmtJabatan = null;
        $datetime = \DateTime::createFromFormat('Y-m-d', $model->tmt_jabatan);
        if($datetime != false) {
            $tmtJabatan = $datetime->format('d-m-Y');
        }

        $tglLahir = null;
        $datetime = \DateTime::createFromFormat('Y-m-d', $model->tanggal_lahir);
        if($datetime != false) {
            $tglLahir = $datetime->format('d-m-Y');
        }

        $list = [
            'id' => @$model->id,
            'nama' => @$model->nama,
            'nip' => @$model->nip,
            'instansi' => @$model->instansi->nama,
            'atasan' => @$model->atasan->nama,
            'golRuangAkhir' => @$model->golongan->golongan,
            'nama_jabatan' => @$model->nama_jabatan,
            'jenis_kelamin' => @$model->gender,
            'tempat_lahir' => @$model->tempat_lahir,
            'tempatLahir' => @$model->tempat_lahir,
            'tanggal_lahir' => @$model->tanggal_lahir,
            'tglLahir' => $tglLahir,
            'alamat' => @$model->alamat,
            'telepon' => @$model->telepon,
            'email' => @$model->email,
            'gelar_depan' => @$model->gelar_depan,
            'gelarDepan' => $model->gelar_depan,
            'gelar_belakang' => @$model->gelar_belakang,
            'gelarBelakang' => $model->gelar_belakang,
            'eselon' => @$model->eselon->nama,
            'tmtGolAkhir' => $tmtGolAkhir,
            'tmtJabatan' => $tmtJabatan,
            'pendidikan_terakhir' => 'Pendidikan Terkahir',
            'tkPendidikanTerakhir' => @$model->pendidikan->nama,
            'nama_kampus' => 'Nama Kampus',
        ];

        return $list;
    }

    public function actionUpdate($nip)
    {
        $model = Pegawai::findOne([
            'nip' => $nip
        ]);

        if($model === null) {
            return [
                'success' => false,
                'message' => 'Pegawai tidak ditemukan'
            ];
        }

        $nama = Yii::$app->request->post('nama');
        if($nama != null) {
            $model->updateAttributes([
                'nama' => $nama
            ]);
        }

        $golRuangAkhir = Yii::$app->request->post('golRuangAkhir');
        if($golRuangAkhir != null) {
            $golongan = Golongan::findOne([
                'golongan' => $golRuangAkhir
            ]);
            $model->updateAttributes([
                'id_golongan' => @$golongan->id
            ]);
        }

        $tkPendidikanTerakhir = Yii::$app->request->post('tkPendidikanTerakhir');
        if($tkPendidikanTerakhir != null) {
            $data = Pendidikan::findOne([
                'nama' => $tkPendidikanTerakhir
            ]);
            if($data !== null) {
                $model->updateAttributes([
                    'id_pendidikan' => $data->id
                ]);

                return [
                    'success' => true,
                    'id_pendidikan' => $data->id,
                    'tkPendidikanTerakhir' => $tkPendidikanTerakhir
                ];
            } else {
                return [
                    'success' => false,
                    'id_pendidikan' => $data->id,
                    'tkPendidikanTerakhir' => $tkPendidikanTerakhir
                ];
            }

        }

        $eselon = Yii::$app->request->post('eselon');
        if($eselon != null) {
            $data = Eselon::findOne([
                'nama' => $eselon
            ]);
            $model->updateAttributes([
                'id_eselon' => @$data->id
            ]);
        }

        $tmtGolAkhir = Yii::$app->request->post('tmtGolAkhir');
        if($tmtGolAkhir != null) {
            $tmt_golongan = null;
            $datetime = \DateTime::createFromFormat('d-m-Y', $tmtGolAkhir);
            if($datetime != false) {
                $tmt_golongan = $datetime->format('Y-m-d');
            }
            $model->updateAttributes([
                'tmt_golongan' => $tmt_golongan
            ]);
        }

        $tmtJabatan = Yii::$app->request->post('tmtJabatan');
        if($tmtJabatan != null) {
            $tmt_jabatan = null;
            $datetime = \DateTime::createFromFormat('d-m-Y', $tmtJabatan);
            if($datetime != false) {
                $tmt_jabatan = $datetime->format('Y-m-d');
            }
            $model->updateAttributes([
                'tmt_jabatan' => $tmt_jabatan
            ]);
            return [
                'success' => true,
                'message' => 'Data berhasil diubah',
                'tmtJabatan' => $tmtJabatan,
                'tmt_jabatan' => $tmt_jabatan
            ];
        }

        $tglLahir = Yii::$app->request->post('tglLahir');
        if($tglLahir != null) {
            $tanggal_lahir = null;

            $datetime = \DateTime::createFromFormat('d-m-Y', $tglLahir);
            if($datetime != false) {
                $tanggal_lahir = $datetime->format('Y-m-d');
            }

            if($tanggal_lahir != null) {
                $model->updateAttributes([
                    'tanggal_lahir' => $tanggal_lahir
                ]);
            }

            return [
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'tglLahir' => $tglLahir,
                'tanggal_lahir' => $tanggal_lahir,
            ];

        }

        $gelarDepan = Yii::$app->request->post('gelarDepan');
        if($gelarDepan != null) {
            $model->updateAttributes([
                'gelar_depan' => $gelarDepan
            ]);
            return [
                'success' => true,
                'gelarDepan' => $gelarDepan,
                'gelar_depan' => $gelarDepan,
            ];
        }

        $gelarBelakang = Yii::$app->request->post('gelarBelakang');
        if($gelarBelakang != null) {
            $model->updateAttributes([
                'gelar_belakang' => $gelarBelakang
            ]);
            return [
                'success' => true,
                'gelarBelakang' => $gelarBelakang,
                'gelar_belakang' => $gelarBelakang,
            ];
        }

        $tempat_lahir = Yii::$app->request->post('tempat_lahir');
        if($tempat_lahir != null) {
            $model->updateAttributes([
                'tempat_lahir' => $tempat_lahir
            ]);
        }

        return [
            'success' => false,
            'message' => 'Gagal'
        ];
    }
}
