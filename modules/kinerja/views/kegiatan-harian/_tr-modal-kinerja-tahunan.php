<?php

use yii\helpers\Html;

/* @var $kegiatanTahunan \app\modules\kinerja\models\KegiatanTahunan */
/* @var $no int */
/* @var $padding int */
/* @var $tanggal string */
/* @var $id_kegiatan_harian_jenis int */
/* @var $bulan int */

?>
<tr>
    <td style="text-align:center;">
        <?= $no; ?>
    </td>
    <td style="text-align:left;padding-left:<?=$padding?>px">
        <?= $kegiatanTahunan->nama ?> <?= @$status_plt ? '(Plt)' : '' ?>
    </td>
    <td style="text-align:center;">
        <?= Html::a('<i class="fa fa-check"></i> Pilih',[
            '/kinerja/kegiatan-harian/create-v2',
            'id_kegiatan_harian_jenis' => @$id_kegiatan_harian_jenis,
            'id_kegiatan_tahunan' => $kegiatanTahunan->id,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
        ],['class'=>'btn btn-xs btn-success btn-flat']); ?>
    </td>
</tr>

<?php /* $padding += 20; foreach($kegiatanBulanan->findAllSub() as $sub) {
    echo $this->render('_tr-modal-kinerja-tahunan', [
        'model' => $sub,
        'tanggal' => $tanggal,
        'id_kegiatan_harian_jenis' => $id_kegiatan_harian_jenis,
        'padding' => $padding,
    ]);
} */ ?>
