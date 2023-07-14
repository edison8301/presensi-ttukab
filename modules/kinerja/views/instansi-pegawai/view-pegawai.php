<?php 

use app\components\Helper;
use yii\web\User;
use yii\widgets\DetailView;


?>

<div class="pegawai-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Detail Pegawai</h3>
    </div>
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => $model->nip,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansiPegawaiBerlaku->getNamaInstansi(),
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => @$model->getNamaJabatan(),
            ],
            [
                'attribute' => 'id_atasan',
                'format' => 'raw',
                'value' => @$model->instansiPegawaiBerlaku->atasan->nama,
            ],
            [
                'label' => 'Kelas Jabatan',
                'value' => @$model->jabatan->kelas_jabatan
            ],
            [
                'label' => 'Nilai Jabatan',
                'value' => @$model->kelasJabatan->getNilaiTengah()
            ],
            [
                'label' => 'Jumlah Tunjangan (100%)',
                'value' => Helper::rp($model->getRupiahTukin()),
            ],
        ],
    ]) ?>
    </div>
    <div class="box-footer">
        <?php if (User::isAdmin()) { ?>
            <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?php } ?>
    </div>
</div>
