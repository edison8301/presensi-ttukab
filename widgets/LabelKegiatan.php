<?php

namespace app\widgets;

use app\models\Kegiatan;
use app\models\KegiatanStatus;
use yii\base\Widget;
use yii\helpers\Html;

class LabelKegiatan extends Widget
{
    public $kegiatan;

    public function run()
    {
        $status = $this->kegiatan->id_kegiatan_status;
        if ($status == KegiatanStatus::DISETUJUI) {
            $context = 'success';
        } elseif ($status == KegiatanStatus::KONSEP) {
            $context = 'warning';
        } elseif ($status == KegiatanStatus::DIVERIFIKASI) {
            $context = 'primary';
        } else {
            $context = 'danger';
        }

        return Label::widget([
            'text' => @$this->kegiatan->kegiatanStatus->nama,
            'context' => $context
        ]);
    }
}
