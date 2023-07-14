<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Instansi */

$this->title = "Detail Instansi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Instansi</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_induk',
                'format' => 'raw',
                'value' => $model->id_induk,
            ],
            [
                'attribute' => 'id_instansi_jenis',
                'format' => 'raw',
                'value' => @$model->instansiJenis->nama,
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'singkatan',
                'format' => 'raw',
                'value' => $model->singkatan,
            ],
            [
                'attribute' => 'alamat',
                'format' => 'raw',
                'value' => $model->alamat,
            ],
            [
                'attribute' => 'telepon',
                'format' => 'raw',
                'value' => $model->telepon,
            ],
            [
                'attribute' => 'email',
                'format' => 'raw',
                'value' => $model->email,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>

<div class="instansi-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Struktur Jabatan</h3>
    </div>
    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Jabatan', ['jabatan/create', 'id_instansi' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

    <div class="box-body">

        <?= $this->render('_modalPegawai', ['instansi' => $model]) ?>

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Nama Jabatan</th>
                <th style="text-align: center;">Status Kepala</th>
                <th style="text-align: center;">Jenis Jabatan</th>
                <th style="text-align: center">Pegawai</th>
                <th></th>
            </tr>
            </thead>
            <?php foreach($model->manyJabatanKepala as $jabatan) {
                echo $this->render('_tr-jabatan', ['jabatan' => $jabatan, 'level' => 0]);
            } ?>
        </table>
    </div>
</div>

<script>
    let id_jabatan = 1;
    let id_pegawai = 1;

    function redirect() {
        window.location.href = '<?= \yii\helpers\Url::to(['jabatan/assign']) ;?>' + '&id_pegawai=' + id_pegawai + '&id_jabatan=' + id_jabatan;
    }
</script>
