<?php

use app\models\User;
use yii\helpers\Html;

?>

<tr>
    <td rowspan="4" style="text-align: center">
        <?php if ($kegiatanTahunan->accessSetPeriksa() or User::isAdmin()) { ?>
            <?= Html::checkbox('selection[]', false, [
                'value' => $kegiatanTahunan->id,
                'class' => 'checkbox-data',
            ]); ?>
        <?php } ?>
    </td>
    <td rowspan="4" style="text-align: center"><?= @$no; ?></td>
    <td rowspan="4">
        <?= @$kegiatanTahunan->kegiatanTahunanAtasan->nama; ?>
    </td>
    <td rowspan="4">
        <?= $kegiatanTahunan->nama; ?>
        <?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?>
        <?= $kegiatanTahunan->getLabelMappingRpjmd() ?>
        <br/>
        <i class="fa fa-user"></i> <?= @$kegiatanTahunan->pegawai->nama; ?><br>
        <i class="fa fa-tag"></i> <?= @$kegiatanTahunan->instansiPegawaiSkp->nomor; ?><br>
        <?= $kegiatanTahunan->getKeteranganTolak(); ?>
    </td>
    <td style="text-align: center">Kuantitas</td>
    <td>
        <?= $kegiatanTahunan->indikator_kuantitas; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_kuantitas; ?>
        <?= $kegiatanTahunan->satuan_kuantitas; ?>
    </td>
    <td rowspan="4" style="text-align: center">
        <?php
            $output = '';

            if($kegiatanTahunan->accessSetPeriksa()) {
                $output .= Html::a('<i class="fa fa-send-o"></i>',['kegiatan-tahunan/set-periksa','id' => $kegiatanTahunan->id],['data-toggle' => 'tooltip','title' => 'Periksa Kegiatan','onclick' => 'return confirm("Yakin akan mengirim kegiatan untuk diperiksa?");']).' ';
            }

            if($kegiatanTahunan->accessSetSetuju()) {
                $output .= Html::a('<i class="fa fa-check"></i>',['kegiatan-tahunan/set-setuju','id' => $kegiatanTahunan->id],['data-toggle' => 'tooltip','title' => 'Setuju Kegiatan','onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']).' ';
            }

            if($kegiatanTahunan->accessSetTolak()) {
                $output .= Html::a('<i class="fa fa-remove"></i>',['kegiatan-tahunan/set-tolak','id' => $kegiatanTahunan->id],['data-toggle' => 'tooltip','title' => 'Tolak Kegiatan','onclick' => 'return confirm("Yakin akan menolak kegiatan?");']).' ';
            }

            $output .= Html::a('<i class="fa fa-comment"></i>', ['kegiatan-tahunan/view-catatan', 'id' => $kegiatanTahunan->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Catatan']) . ' ';

            $output .= Html::a('<i class="fa fa-pencil-square-o"></i>', ['kegiatan-tahunan/update-rpjmd-indikator', 'id' => $kegiatanTahunan->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Renstra']) . ' ';

            if($kegiatanTahunan->accessView()) {
                $output .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['kegiatan-tahunan/view-v2', 'id' => $kegiatanTahunan->id, 'mode' => $searchModel->mode], ['data-toggle' => 'tooltip', 'title' => 'Lihat']) . ' ';
            }

            if($kegiatanTahunan->accessUpdate()) {
                $output .= Html::a('<i class="glyphicon glyphicon-pencil"></i>',['kegiatan-tahunan/update-v2','id' => $kegiatanTahunan->id],['data-toggle' => 'tooltip','title' => 'Ubah']).' ';
            }

            if($kegiatanTahunan->accessDelete()) {
                $output .= Html::a('<i class="glyphicon glyphicon-trash"></i>',['kegiatan-tahunan/delete','id' => $kegiatanTahunan->id],['data-toggle' => 'tooltip','title' => 'Hapus','onclick' => 'return confirm("Yakin akan menghapus data?");']).' ';
            }

            print trim($output);

        ?>
    </td>
</tr>
<tr>
    <td style="text-align: center">Kualitas</td>
    <td>
        <?= $kegiatanTahunan->indikator_kualitas; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_kualitas; ?>
        <?= $kegiatanTahunan->satuan_kualitas; ?>
    </td>
</tr>
<tr>
    <td style="text-align: center">Waktu</td>
    <td>
        <?= $kegiatanTahunan->indikator_waktu; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_waktu; ?>
        <?= $kegiatanTahunan->satuan_waktu; ?>
    </td>
</tr>
<tr>
    <td style="text-align: center">Biaya</td>
    <td>
        <?= $kegiatanTahunan->indikator_biaya; ?>
    </td>
    <td style="text-align: center">
        <?= $kegiatanTahunan->target_biaya; ?>
        <?= $kegiatanTahunan->satuan_biaya; ?>
    </td>
</tr>

<?php $i=1; foreach ($kegiatanTahunan->manySub as $sub) {
    echo $this->render('_tr-index-kegiatan-tahunan-v2', [
        'kegiatanTahunan' => $sub,
        'searchModel' => $searchModel,
        'no' => $no.'.'.$i,
    ]);
    $i++;
} ?>
