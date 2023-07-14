<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiRekapAbsensi */

$this->title = "Detail Pegawai Rekap Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Rekap Absensi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-rekap-absensi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Pegawai Rekap Absensi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => $model->id,
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => $model->id_pegawai,
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => $model->bulan,
            ],
            [
                'attribute' => 'tahun',
                'format' => 'raw',
                'value' => $model->tahun,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => $model->id_instansi,
            ],
            [
                'attribute' => 'id_golongan',
                'format' => 'raw',
                'value' => $model->id_golongan,
            ],
            [
                'attribute' => 'jumlah_hari_kerja',
                'format' => 'raw',
                'value' => $model->jumlah_hari_kerja,
            ],
            [
                'attribute' => 'jumlah_hadir',
                'format' => 'raw',
                'value' => $model->jumlah_hadir,
            ],
            [
                'attribute' => 'jumlah_tidak_hadir',
                'format' => 'raw',
                'value' => $model->jumlah_tidak_hadir,
            ],
            [
                'attribute' => 'jumlah_izin',
                'format' => 'raw',
                'value' => $model->jumlah_izin,
            ],
            [
                'attribute' => 'jumlah_sakit',
                'format' => 'raw',
                'value' => $model->jumlah_sakit,
            ],
            [
                'attribute' => 'jumlah_cuti',
                'format' => 'raw',
                'value' => $model->jumlah_cuti,
            ],
            [
                'attribute' => 'jumlah_tugas_belajar',
                'format' => 'raw',
                'value' => $model->jumlah_tugas_belajar,
            ],
            [
                'attribute' => 'jumlah_tugas_kedinasan',
                'format' => 'raw',
                'value' => $model->jumlah_tugas_kedinasan,
            ],
            [
                'attribute' => 'jumlah_dinas_luar',
                'format' => 'raw',
                'value' => $model->jumlah_dinas_luar,
            ],
            [
                'attribute' => 'jumlah_tanpa_keterangan',
                'format' => 'raw',
                'value' => $model->jumlah_tanpa_keterangan,
            ],
            [
                'attribute' => 'jumlah_tidak_hadir_apel_pagi',
                'format' => 'raw',
                'value' => $model->jumlah_tidak_hadir_apel_pagi,
            ],
            [
                'attribute' => 'jumlah_tidak_hadir_apel_sore',
                'format' => 'raw',
                'value' => $model->jumlah_tidak_hadir_apel_sore,
            ],
            [
                'attribute' => 'jumlah_tidak_hadir_upacara',
                'format' => 'raw',
                'value' => $model->jumlah_tidak_hadir_upacara,
            ],
            [
                'attribute' => 'jumlah_tidak_hadir_senam',
                'format' => 'raw',
                'value' => $model->jumlah_tidak_hadir_senam,
            ],
            [
                'attribute' => 'jumlah_tidak_hadir_sidak',
                'format' => 'raw',
                'value' => $model->jumlah_tidak_hadir_sidak,
            ],
            [
                'attribute' => 'persen_potongan_fingerprint',
                'format' => 'raw',
                'value' => $model->persen_potongan_fingerprint,
            ],
            [
                'attribute' => 'persen_potongan_kegiatan',
                'format' => 'raw',
                'value' => $model->persen_potongan_kegiatan,
            ],
            [
                'attribute' => 'persen_potongan_total',
                'format' => 'raw',
                'value' => $model->persen_potongan_total,
            ],
            [
                'attribute' => 'status_kunci',
                'format' => 'raw',
                'value' => $model->status_kunci,
            ],
            [
                'attribute' => 'waktu_diperbarui',
                'format' => 'raw',
                'value' => $model->waktu_diperbarui,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Pegawai Rekap Absensi', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai Rekap Absensi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
