<?php

namespace app\widgets;

use app\modules\kinerja\models\KegiatanTahunan;
use yii\base\Widget;
use yii\helpers\Html;

/* @property KegiatanTahunan $kegiatan */

class AlertKegiatan extends Widget
{
    public $kegiatan;

    public function run()
    {
        $kegiatan = $this->kegiatan;

        $alert = [];

        if($kegiatan->status_kegiatan_tahunan_atasan == 1 AND @$kegiatan->kegiatanTahunanAtasan == null) {
            $text = 'Kegiatan Atasan belum ditentukan. Silahkan klik ';
            $text .= Html::a('di sini',[
                '/kinerja/kegiatan-tahunan/update-id-kegiatan-tahunan-atasan',
                'id' => $kegiatan->id
            ]);

            $text .= ' untuk memilih kegiatan atasan yang didukung';

            $alert[] = [
                'text' => $text,
                'context' => 'warning'
            ];
        }

        $output = '';

        foreach($alert as $item) {
            $output .= Html::tag('div', '<i class="fa fa-warning"></i> ' . $item['text'], [
                'class' => 'alert alert-' . $item['context']
            ]);
        }

        return $output;

    }
}
