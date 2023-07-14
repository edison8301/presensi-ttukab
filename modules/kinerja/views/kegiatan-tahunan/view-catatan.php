<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Catatan';

?>

<div class="box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Kinerja Tahunan</h3>
    </div>

    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th style="text-align:right; width: 180px">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'id_pegawai',
                    'format' => 'raw',
                    'value' => $model->getNamaNipPegawai(),
                ],
                [
                    'label' => 'Nomor SKP',
                    'format' => 'raw',
                    'value' => $model->getNomorSkpLengkap(),
                ],
                [
                    'label' => 'Kegiatan Atasan yang Didukung',
                    'format' => 'raw',
                    'value' => @$model->kegiatanTahunanAtasan->nama,
                    'visible' => @$model->isVisibleIdKegiatanTahunanAtasan() AND $model->status_kegiatan_tahunan_atasan == 1 AND $model->id_kegiatan_tahunan_versi == 2
                ],
                [
                    'attribute' => 'id_kegiatan_rhk',
                    'format' => 'raw',
                    'visible' => $model->id_kegiatan_tahunan_versi = 3,
                    'value' => Html::encode(@$model->kegiatanRhk->nama),
                ],
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => Html::encode($model->nama),
                ],
                [
                    'attribute' => 'id_kegiatan_status',
                    'format' => 'raw',
                    'value' => $model->kegiatanStatus ? $model->kegiatanStatus->getLabel() : '',
                ],
            ],
        ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kinerja Tahunan', ['index-v2'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>
</div>

<?= Html::beginForm(); ?>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Catatan</h3>
    </div>

    <div class="box-body">
        <?php foreach ($model->findAllCatatan() as $data) { ?>
            <p>
                <?= $data->getNamaUser() ?> <br>
                <span style="font-size: 11px;">
                    <?= Helper::getWaktuWIB($data->waktu_buat) ?>
                </span>
            </p>
            <p>Catatan : <b><?= $data->catatan ?></b> &nbsp;
                <?php if($data->accessDelete()) {
                    echo Html::a('<i class="fa fa-trash"></i>', [
                        '/catatan/delete',
                        'id' => $data->id,
                    ], [
                        'data-toggle' => 'tooltip',
                        'title' => 'Hapus Catatan',
                        'data-method' => 'post',
                        'data-confirm' => 'Yakin akan menghapus catatan?'
                    ]);
                } ?>
            </p>
            <hr>
        <?php } ?>
    </div>

    <div class="box-footer">
        <div class="row">
            <div class="col-sm-6">
                <?= Html::textInput('catatan', null, ['class' => 'form-control', 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-sm-2">
                <?= Html::submitButton('Kirim', ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
        </div>
    </div>
</div>

<?php Html::endForm(); ?>
