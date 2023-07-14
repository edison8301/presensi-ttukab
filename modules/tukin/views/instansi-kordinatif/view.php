<?php

use app\components\Helper;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\InstansiKordinatif */

$this->title = "Detail Instansi Kordinatif";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Kordinatif', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-kordinatif-view box box-primary">
    <div class="box-header">
        <h3 class="box-title">Detail Instansi Kordinatif</h3>
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
                'attribute' => 'tanggal_berlaku_mulai',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal_berlaku_mulai),
            ],
            [
                'attribute' => 'tanggal_berlaku_selesai',
                'format' => 'raw',
                'value' => Helper::getTanggal($model->tanggal_berlaku_selesai),
            ],
        ],
    ]) ?>
    </div>
    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Instansi Kordinatif', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Instansi Kordinatif', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Daftar Besaran</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="text-align: center;width: 30px;">No</th>
                <th style="text-align: center;">Kelompok</th>
                <th style="text-align: center;">Besaran</th>
                <th style="text-align: center;">Jumlah Pegawai</th>
            </tr>
            </thead>
            <?php $i = 1; ?>
            <?php foreach ($model->findOrCreateBesaran() as $besaran) { ?>
                <tr>
                    <td style="text-align: center;"><?= $i++; ?></td>
                    <td style="text-align: center;"><?= $besaran->getKelompok() ?></td>
                    <td style="text-align: center;">
                        <?= Editable::widget([
                            'model' => $besaran,
                            'name' => 'besaran',
                            'value' => $besaran->besaran,
                            'displayValue' => Helper::rp($besaran->besaran),
                            'beforeInput' => Html::hiddenInput('editableKey', $besaran->id),
                            'asPopover' => true,
                            'placement' => 'top',
                            'formOptions' => ['action' => ['/tukin/instansi-kordinatif-besaran/editable-update']],
                            'header' => 'Jumlah',
                            'inputType' => Editable::INPUT_TEXT,
                            'options'=>[
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ]
                            ]
                        ]); ?>
                    </td>
                    <td style="text-align: center"><?= $besaran->getJumlahPegawai() ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
