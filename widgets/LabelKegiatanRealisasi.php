<?php

namespace app\widgets;

use app\models\KegiatanRealisasi;
use app\models\RefKegiatanRealisasiStatus;
use yii\base\Widget;
use yii\helpers\Html;

class LabelKegiatanRealisasi extends Widget
{
    public $kegiatanRealisasi;

    public function run()
    {
        $status = $this->kegiatanRealisasi->kode_kegiatan_realisasi_status;
        if ($status == RefKegiatanRealisasiStatus::DISETUJUI) {
            $context = 'success';
        } elseif ($status == RefKegiatanRealisasiStatus::KONSEP) {
            $context = 'warning';
        } elseif ($status == RefKegiatanRealisasiStatus::DIVERIFIKASI) {
            $context = 'primary';
        } else {
            $context = 'danger';
        }

        return Label::widget([
            'text' => $this->kegiatanRealisasi->refKegiatanRealisasiStatus->nama,
            'context' => $context
        ]);
    }
}
