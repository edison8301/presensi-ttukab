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
                    'label' => 'Pegawai',
                    'format' => 'raw',
                    'value' => Html::encode(@$model->pegawai->nama),
                ],
                [
                    'attribute' => 'uraian',
                    'format' => 'raw',
                    'value' => Html::encode($model->uraian),
                ],
                [
                    'attribute' => 'id_kegiatan_tahunan',
                    'format' => 'raw',
                    'value' => Html::encode($model->getNamaKegiatanTahunan()),
                ],
                [
                    'attribute' => 'tanggal',
                    'format' => 'date',
                    'value' => Html::encode($model->tanggal),
                ],
                [
                    'attribute' => 'id_kegiatan_status',
                    'format' => 'raw',
                    'value' => @$model->kegiatanStatus->getLabel(),
                ],
            ],
        ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Kinerja Harian', ['index-v2'], ['class' => 'btn btn-primary btn-flat']) ?>
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