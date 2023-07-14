<?php

namespace app\commands;

use app\modules\tunjangan\models\JabatanTunjanganPelaksana;
use app\modules\tunjangan\models\JabatanTunjanganStruktural;
use yii\console\Controller;

class JabatanTunjanganPelaksanaController extends Controller
{
    public function actionIndex()
    {
        $this->stdout('JabatanTunjanganStrukturalController');
    }
    public function actionUpdateIdJabatanTunjanganGolongan()
    {
        $query = JabatanTunjanganPelaksana::find();

        foreach($query->all() as $data) {
            $id_jabatan_tunjangan_golongan = null;

            if($data->id_jabatan_golongan == 1) {
                $id_jabatan_tunjangan_golongan = 4;
            }

            if($data->id_jabatan_golongan == 2) {
                $id_jabatan_tunjangan_golongan = 3;
            }

            if($data->id_jabatan_golongan == 3) {
                $id_jabatan_tunjangan_golongan = 2;
            }

            if($data->id_jabatan_golongan == 4) {
                $id_jabatan_tunjangan_golongan = 1;
            }

            $data->updateAttributes([
                'id_jabatan_tunjangan_golongan' => $id_jabatan_tunjangan_golongan
            ]);
        }
    }
}
