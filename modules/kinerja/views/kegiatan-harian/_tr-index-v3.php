<?php

use app\components\Helper;
use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;
use yii\helpers\Html;

/* @var $searchModel \app\modules\kinerja\models\KegiatanHarianSearch */
/* @var $kegiatanHarian \app\modules\kinerja\models\KegiatanHarian */
/* @var $no int */

?>

<tr>
    <td style="text-align: center">
        <?php if ($kegiatanHarian->canVerifikasi()) { ?>
            <?= Html::checkbox('selection[]', false, [
                'value' => $kegiatanHarian->id,
                'class' => 'checkbox-data',
            ]); ?>
        <?php } ?>
    </td>
    <td style="text-align: center"><?= $no; ?></td>
    <td style="text-align: center"><?= Helper::getTanggal($kegiatanHarian->tanggal); ?></td>
    <td>
        <?= $kegiatanHarian->uraian ?>
        <?= $kegiatanHarian->kegiatanStatus->getLabel(); ?><br>
        <i class="fa fa-user"></i> <?= @$kegiatanHarian->pegawai->nama; ?><br>
        <?= $kegiatanHarian->getKeteranganTolak(); ?>
    </td>
    <td style="text-align: center">
        <?= @$kegiatanHarian->kegiatanTahunan->kegiatanAspek->nama; ?>
    </td>
    <td>
        <?= @$kegiatanHarian->kegiatanTahunan->nama; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanHarian->realisasi; ?>
    </td>
    <td style="text-align: center">
        <?php
        $output = '';

        if($kegiatanHarian->accessSetPeriksa()) {
            $output .= Html::a('<i class="fa fa-send-o"></i>',[
                '/kinerja/kegiatan-harian/set-periksa',
                'id'=>$kegiatanHarian->id,
            ],[
                'data-toggle'=>'tooltip',
                'title'=>'Kirim Untuk Diperiksa',
                'onclick'=>'return confirm("Yakin akan mengirim data?");',
            ]).' ';
        }

        if($kegiatanHarian->accessSetSetuju()) {
            $output .= Html::a('<i class="fa fa-check"></i>', ['/kinerja/kegiatan-harian/set-setuju', 'id' => $kegiatanHarian->id], ['data-toggle' => 'tooltip', 'title' => 'Setujui Kegiatan', 'onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']) . ' ';
        }

        if($kegiatanHarian->accessSetKonsep()) {
            $output .= Html::a('<i class="fa fa-refresh"></i>', ['/kinerja/kegiatan-harian/set-konsep', 'id' => $kegiatanHarian->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Jadi Konsep', 'onclick' => 'return confirm("Yakin akan mengubah kegiatan jadi konsep?");']) . ' ';
        }

        if($kegiatanHarian->accessSetTolak()) {
            $output .= Html::a('<i class="fa fa-remove"></i>', ['/kinerja/kegiatan-harian/tolak', 'id' => $kegiatanHarian->id], ['data-toggle' => 'tooltip', 'title' => 'Tolak Kegiatan']) . ' ';
        }

        $output .= Html::a('<i class="fa fa-comment"></i>', ['/kinerja/kegiatan-harian/view-catatan', 'id' => $kegiatanHarian->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Catatan']) . ' ';

        $output .= Html::a('<i class="fa fa-eye"></i>',['/kinerja/kegiatan-harian/view-v4','id'=>$kegiatanHarian->id,'mode'=>$searchModel->mode],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';

        if($kegiatanHarian->accessUpdate()) {
            $output .= Html::a('<i class="fa fa-pencil"></i>', ['/kinerja/kegiatan-harian/update-v4', 'id' => $kegiatanHarian->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah']) . ' ';
        }

        if($kegiatanHarian->accessDelete()) {
            $output .= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-harian/delete', 'id' => $kegiatanHarian->id], ['data-toggle' => 'tooltip', 'title' => 'Hapus', 'onclick' => 'return confirm("Yakin akan menghapus data?");']) . ' ';
        }

        print trim($output);

        ?>
    </td>
</tr>
