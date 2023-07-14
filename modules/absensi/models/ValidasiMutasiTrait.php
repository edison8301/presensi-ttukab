<?php


namespace app\modules\absensi\models;


use app\components\Helper;
use app\models\Instansi;
use app\models\User;

trait ValidasiMutasiTrait
{
    public function validasiMutasiPegawai($attribute, $params, $validator)
    {
        if (User::isInstansi() || User::isAdminInstansi()) {
            $id_instansi = User::getListIdInstansi();
            foreach ($this->pegawai->allInstansiPegawai as $instansiPegawai) {
                $datetimeMulai = \DateTime::createFromFormat('Y-m-d', $instansiPegawai->tanggal_mulai);
                $datetimeSelesai = \DateTime::createFromFormat('Y-m-d', $instansiPegawai->tanggal_selesai);
                if ($this->$attribute >= $datetimeMulai->format('Y-m-01')
                    && $this->$attribute <= $datetimeSelesai->format('Y-m-t')
                    && in_array($instansiPegawai->id_instansi, $id_instansi, false)) {
                    return true;
                }
            }
            $instansi = Instansi::findOne(User::getIdInstansi());
            $this->addError($attribute, "Pegawai sudah tidak di Instansi $instansi->nama dan UPTD nya Pada Tanggal "
                . Helper::getTanggal($this->$attribute));
        }
    }
}
