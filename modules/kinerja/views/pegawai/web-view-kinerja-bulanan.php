<?php

use app\components\Helper;
use yii\helpers\Html;

?>

<?php if ($back) { ?>
    <div style="margin-bottom:20px;">
        <?= Html::a('<i class="fa fa-arrow-left"></i> Kembali', [
            '/kinerja/pegawai/web-view-kinerja',
            'nip' => $pegawai->nip
        ],['class' => 'btn btn-primary btn-flat btn-block']); ?>
    </div>
<?php } ?>

<?= $this->render('_filter-index', [
    'action' => ['/kinerja/pegawai/web-view-kinerja-bulanan','nip' => $pegawai->nip],
    'searchModel' => $searchModel,
]); ?>

<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h2 class="box-title"> <i class="fa fa-calendar"></i> Daftar Kinerja Bulanan</h2>
    </div>

    <div class="box-body">
        <div style="overflow:auto;">
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Kinerja Bulanan</th>
                    <th style="text-align: center">Nomor SKP</th>
                    <th style="text-align: center">Bulan</th>
                    <th style="text-align: center">Jenis</th>
                    <th style="text-align: center">Aspek</th>
                    <th style="text-align: center; width: 100px;">Target</th>
                    <th style="text-align: center">Realisasi</th>
                    <th style="text-align: center">% Realisasi</th>
                    <th>Status</th>
                </tr>
                <?php
                $total_persen = 0;
                $persenRealisasiTotal = 0;
                $i=1;
                ?>
                <?php foreach ($allKegiatanBulanan as $kegiatanBulanan) { ?>
                    <tr>
                        <td class="text-center" rowspan="4"><?= $i; ?></td>
                        <td rowspan="4">
                            <?= $kegiatanBulanan->kegiatanTahunan->nama ?>
                        </td>
                        <td class="text-center" rowspan="4">
                            <?= @$kegiatanBulanan->instansiPegawaiSkp->nomor; ?>
                        </td>
                        <td class="text-center" rowspan="4">
                            <?= $kegiatanBulanan->getNamaBulanSingkat(); ?>
                        </td>
                        <td style="text-align: center" rowspan="4">
                            <?= $kegiatanBulanan->kegiatanTahunan ? $kegiatanBulanan->kegiatanTahunan->getTextInduk() : ''; ?>
                        </td>
                        <td style="text-align: center">
                            Kuantitas
                        </td>
                        <td style="text-align: center">
                            <?= $target = $kegiatanBulanan->target; ?> <?= $kegiatanBulanan->getSatuanKuantitas(); ?>
                        </td>
                        <td style="text-align: center">
                            <?php $label = Helper::rp($realisasi = $kegiatanBulanan->realisasi,0,0); ?>
                            <?php $label .= ' '.$kegiatanBulanan->getSatuanKuantitas(); ?>
                            <?= $label ?>
                        </td>
                        <td style="text-align: center" rowspan="4">
                            <?= Helper::rp($persenRealisasi = $kegiatanBulanan->persen_realisasi,0,1); ?>
                        </td>
                        <td style="text-align: center" rowspan="4">
                            <?= @$kegiatanBulanan->kegiatanTahunan->labelIdKegiatanStatus; ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Kualitas</td>
                        <td style="text-align: center">
                            <?= $kegiatanBulanan->getTargetSatuanKualitas() ?>
                        </td>
                        <td style="text-align: center">
                            <?php $label = Helper::rp($realisasi = $kegiatanBulanan->realisasi_kualitas,0,0); ?>
                            <?php $label .= ' '.@$kegiatanBulanan->kegiatanTahunan->satuan_kualitas; ?>
                            <?= $label ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Waktu</td>
                        <td style="text-align: center">
                            <?= $kegiatanBulanan->getTargetSatuanWaktu() ?>
                        </td>
                        <td style="text-align: center">
                            <?php $label = Helper::rp($realisasi = $kegiatanBulanan->realisasi_waktu,0,0); ?>
                            <?php $label .= ' '.@$kegiatanBulanan->kegiatanTahunan->satuan_waktu; ?>
                            <?= $label ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">Biaya</td>
                        <td style="text-align: center">
                            <?= $kegiatanBulanan->getTargetSatuanBiaya() ?>
                        </td>
                        <td style="text-align: center">
                            <?php $label = Helper::rp($realisasi = $kegiatanBulanan->realisasi_biaya,0,0); ?>
                            <?php $label .= ' '.@$kegiatanBulanan->kegiatanTahunan->satuan_biaya; ?>
                            <?= $label ?>
                        </td>
                    </tr>

                    <?php
                        $persenRealisasiTotal += $persenRealisasi;
                    ?>

                    <?php $i++; } ?>
                <?php
                $jumlah = $i-1;

                if($jumlah == 0) {
                    $jumlah = 1;
                }
                ?>
                <tr>
                    <th colspan="8" class="text-center">RATA - RATA</th>
                    <th style="text-align: center"><?= Helper::rp($persenRealisasiTotal/$jumlah,0,1); ?></th>
                    <th colspan="3">&nbsp;</th>
                </tr>
            </table>
        </div>
    </div>
</div>