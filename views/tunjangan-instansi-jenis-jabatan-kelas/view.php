<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\components\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganInstansiJenisJabatanKelas */

$this->title = "Detail Tunjangan Unit Jenis Jabatan Kelas";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Unit Jenis Jabatan Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-unit-jenis-jabatan-kelas-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Tunjangan Unit Jenis Jabatan Kelas</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansi->nama,
            ],
            [
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'value' => @$model->getNamaJenisJabatan(),
            ],
            [
                'attribute' => 'kelas_jabatan',
                'format' => 'raw',
                'value' => $model->kelas_jabatan,
            ],
            [
                'attribute' => 'nilai_tpp',
                'format' => 'raw',
                'value' => Helper::rp($model->nilai_tpp,0),
            ],
            [
                'attribute' => 'beban_kerja_persen',
                'format' => 'raw',
                'value' => $model->beban_kerja_persen,
            ],
            [
                'attribute' => 'prestasi_kerja_persen',
                'format' => 'raw',
                'value' => $model->prestasi_kerja_persen,
            ],
            [
                'attribute' => 'kondisi_kerja_persen',
                'format' => 'raw',
                'value' => $model->kondisi_kerja_persen,
            ],
            [
                'attribute' => 'tempat_bertugas_persen',
                'format' => 'raw',
                'value' => $model->tempat_bertugas_persen,
            ],
            [
                'attribute' => 'kelangkaan_profesi_persen',
                'format' => 'raw',
                'value' => $model->kelangkaan_profesi_persen,
            ],
            [
                'label' => 'Besaran Beban Kerja',
                'format' => 'raw',
                'value' => Helper::rp($model->getBesaran('beban_kerja_persen')),
            ],
            [
                'label' => 'Besaran Prestasi Kerja',
                'format' => 'raw',
                'value' => Helper::rp($model->getBesaran('prestasi_kerja_persen')),
            ],
            [
                'label' => 'Besaran Kondisi Kerja',
                'format' => 'raw',
                'value' => Helper::rp($model->getBesaran('kondisi_kerja_persen')),
            ],
            [
                'label' => 'Besaran Tempat Bertugas',
                'format' => 'raw',
                'value' => Helper::rp($model->getBesaran('tempat_bertugas_persen')),
            ],
            [
                'label' => 'Besaran Kelangkaan Profesi',
                'format' => 'raw',
                'value' => Helper::rp($model->getBesaran('kelangkaan_profesi_persen')),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Tunjangan Unit Jenis Jabatan Kelas', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Tunjangan Unit Jenis Jabatan Kelas', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
