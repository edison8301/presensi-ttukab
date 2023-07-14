<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\InstansiPegawaiSkp */
/* @var $debug bool */

$this->title = "Detail SKP Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Pegawai Skp', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-skp-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail SKP Pegawai</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'label' => 'Nama Pegawai',
                'value' => @$model->pegawai->nama.' ('.@$model->pegawai->nip.')'
            ],
            [
                'label' => 'Perangkat Daerah',
                'format' => 'raw',
                'value' => $model->instansi->nama,
            ],
            [
                'label' => 'Jabatan',
                'format' => 'raw',
                'value' => $model->instansiPegawai->namaJabatan,
            ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'label' => 'Tanggal Berlaku',
                'format' => 'raw',
                'value' => Helper::getTanggalSingkat($model->tanggal_mulai).' - '.Helper::getTanggalSingkat($model->tanggal_selesai),
            ],
            [
                'label'=>'/pegawai/view',
                'format' => 'raw',
                'value' => Html::a($model->pegawai->id,['/pegawai/view','id'=>$model->pegawai->id]),
                'visible'=>(@$debug==true)
            ],
            [
                'label'=>'/instansi-pegawai/view',
                'format' => 'raw',
                'value' => Html::a($model->instansiPegawai->id,['/instansi-pegawai/view','id'=>$model->instansiPegawai->id]),
                'visible'=>(@$debug==true)
            ]
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar SKP', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF', ['instansi-pegawai-skp/export-pdf-skp', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat','target' => '_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel', ['instansi-pegawai-skp/export-excel-skp', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

</div>

<div class="box box-primary">
    <div class="box-body">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th colspan="3">I. Pejabat Penilai</th>
                <th style="text-align: center">No</th>
                <th colspan="7">II. Pegawai Negeri Sipil yang Dinilai</th>
            </tr>
            </thead>
            <tr>
                <td style="text-align:center">1</td>
                <td style="width:160px">Nama</td>
                <td colspan="2"><?= $model->instansiPegawai->atasan ? $model->instansiPegawai->atasan->nama : ""; ?></td>
                <td style="text-align: center">1</td>
                <td colspan="2">Nama</td>
                <td colspan="5"><?= Html::encode($model->pegawai->nama); ?></td>
            </tr>
            <tr>
                <td style="text-align:center">2</td>
                <td style="width:160px">NIP</td>
                <td colspan="2"><?= $model->instansiPegawai->atasan ? $model->instansiPegawai->atasan->nip : ""; ?></td>
                <td style="text-align: center">2</td>
                <td colspan="2">NIP</td>
                <td colspan="5"><?= $model->pegawai->nip; ?></td>
            </tr>
            <tr>
                <td style="text-align:center">3</td>
                <td style="width:160px">Pangkat/Gol.Ruang</td>
                <td colspan="2"><?= @$model->instansiPegawai->atasan->golongan->golongan ?></td>
                <td style="text-align: center">3</td>
                <td colspan="2">Pangkat/Gol.Ruang</td>
                <td colspan="5"><?= @$model->pegawai->golongan->golongan ?></td>
            </tr>
            <tr>
                <td style="text-align:center">4</td>
                <td style="width:160px">Jabatan</td>
                <td colspan="2"><?= $model->instansiPegawai->atasan ? $model->instansiPegawai->atasan->namaJabatan : ""; ?></td>
                <td style="text-align: center">4</td>
                <td colspan="2">Jabatan</td>
                <td colspan="5"><?= Html::encode($model->instansiPegawai->namaJabatan); ?></td>
            </tr>
            <tr>
                <td style="text-align:center">5</td>
                <td style="width:160px">Perangkat Daerah</td>
                <td colspan="2"><?= @$model->instansiPegawai->atasan ? @$model->instansiPegawai->atasan->getNamaInstansi() : ""; ?></td>
                <td style="text-align: center">5</td>
                <td colspan="2">Perangkat Daerah</td>
                <td colspan="5"><?= $model->instansiPegawai->instansi->nama; ?></td>
            </tr>
            <thead>
            <tr>
                <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                <th style="vertical-align:middle;text-align: center" rowspan="2" colspan="3">III. Kegiatan Tugas Jabatan</th>
                <th style="vertical-align:middle;text-align: center" rowspan="2">AK</th>
                <th style="vertical-align:middle;text-align: center;" colspan="6">Target</th>
                <th style="vertical-align:middle;text-align: center" rowspan="2">Status</th>
            </tr>
            <tr>
                <th colspan="2" style="text-align: center;">Kuant/Output</th>
                <th style="text-align: center;">Kual/Mutu</th>
                <th colspan="2" style="text-align: center;">Waktu</th>
                <th style="text-align: center;">Biaya</th>
            </tr>
            </thead>
            <?php $total_ak = 0; ?>
            <?php $allKegiatanTahunan = $model->findAllKegiatanTahunan([
                'id_kegiatan_tahunan_versi' => 1,
            ]) ?>
            <?php $i=1; foreach($allKegiatanTahunan as $kegiatanTahunan) { ?>
                <?php $total_ak += $kegiatanTahunan->target_angka_kredit; ?>
                <tr>
                    <td style="text-align:center; width: 50px"><?= $i; ?></td>
                    <td colspan="2"><?= Html::encode($kegiatanTahunan->nama); ?></td>
                    <td style="width: 25px">&nbsp;</td>
                    <td style="width: 50px"><?= $kegiatanTahunan->target_angka_kredit; ?></td>
                    <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->target_kuantitas; ?></td>
                    <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->satuan_kuantitas; ?></td>
                    <td style="text-align: center; width: 50px">100</td>
                    <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->target_waktu; ?></td>
                    <td style="text-align: center; width: 50px">Bulan</td>
                    <td style="text-align: right; width: 50px"><?= Helper::rp($kegiatanTahunan->target_biaya); ?></td>
                    <td style="text-align: center; width: 50px"><?= $kegiatanTahunan->getLabelIdKegiatanStatus(); ?></td>
                </tr>
                <?php $i++; } ?>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2" style="text-align: center;">Jumlah Angka Kredit</td>
                <td>&nbsp;</td>
                <td><?= $total_ak; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
