<?php

namespace app\components;

use DateTime;
use app\models\Pengaturan;

abstract class RangeActiveRecord extends \yii\db\ActiveRecord
{
    abstract public function getPegawai();

    abstract public function getTanggal();

    public function getIsInRange($date = null)
    {
        if($date == null) {
            $date = date('Y-m-d');
        }

        if ($this->pegawai === null) {
            return false;
        }

        if (Pengaturan::nilaiByNama('batas_pengajuan') != 1) {
            return true;
        }

        if ($this->tanggal < Pengaturan::getPengaturanByNama('tanggal_batas_pengajuan_berlaku')->nilai) {
            return true;
        }

        $batasHariKerja = Pengaturan::getPengaturanByNama('jumlah_batas_pengajuan_hari_kerja')->nilai;
        $tanggalObj = new DateTime($date);
        $tanggalModel = new DateTime($this->tanggal);
        if (($diff = $tanggalObj->diff($tanggalModel)->format("%a")) <= $batasHariKerja) {
            return true;
        }

        $pegawai = $this->pegawai;
        $i = 0;
        while (true) {
            $tanggal = $tanggalObj->format("Y-m-d");
            if ($this->tanggal === $tanggal) {
                return true;
            }

            $shiftKerja = $pegawai->getShiftKerjaAktif($tanggal);
            if ($shiftKerja->getHasJamKerja($tanggalObj)) {
                $i++;
            } else {
                $tanggalObj->modify('-1 day');
                continue;
            }

            if ($i === $batasHariKerja AND $this->tanggal < $tanggal) {
                return false;
            }

            if ($i > $batasHariKerja) {
                return false;
            }

            $tanggalObj->modify('-1 day');
        }
    }
}
