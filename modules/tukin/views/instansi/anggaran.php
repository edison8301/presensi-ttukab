<?php

use app\components\Helper;
use app\modules\tukin\models\InstansiSerapanAnggaran;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model \app\modules\tukin\models\Instansi */

$this->title = "Serapan Anggaran Instansi";
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
</div>

<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Serapan Anggaran Instansi Tahun 2018</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="text-align: center">Bulan</th>
                <th style="text-align: center" colspan="2">Capaian</th>
            </tr>
            </thead>
            <?php foreach ($model->findOrCreateSerapanAnggaranTahun() as $serapanAnggaran) { ?>
                <tr>
                    <th rowspan="4" style="text-align: center; vertical-align: middle; width: 300px"><?= Helper::getBulanLengkap($serapanAnggaran->bulan); ?></th>
                    <th style="text-align: center; width: 200px">Target</th>
                    <td>
                        <?= InstansiSerapanAnggaran::accessCreate()
                            ? Editable::widget([
                                'model' => $serapanAnggaran,
                                'value' => $serapanAnggaran->target,
                                'name' => 'target',
                                'valueIfNull' =>  '...',
                                'header' => 'Target',
                                'formOptions' => ['action' => ['instansi-serapan-anggaran/editable-update']],
                                'beforeInput' => Html::hiddenInput('editableKey', $serapanAnggaran->id),
                                'inputType' => Editable::INPUT_TEXT,
                                'placement' => 'top',
                                'options' => ['placeholder' => 'Input Target...'],
                                //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                            ])
                            : $serapanAnggaran->target  ?>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: center">Realisasi</th>
                    <td>
                        <?= InstansiSerapanAnggaran::accessCreate()
                            ? Editable::widget([
                                'model' => $serapanAnggaran,
                                'value' => $serapanAnggaran->realisasi,
                                'name' => 'realisasi',
                                'valueIfNull' =>  '...',
                                'header' => 'Target',
                                'formOptions' => ['action' => ['instansi-serapan-anggaran/editable-update']],
                                'beforeInput' => Html::hiddenInput('editableKey', $serapanAnggaran->id),
                                'inputType' => Editable::INPUT_TEXT,
                                'placement' => 'top',
                                'options' => ['placeholder' => 'Input Realisasi...'],
                                //'pluginEvents' =>["editableSuccess"=>"function(event,val,form,data) { location.reload(); }"]
                            ])
                            : $serapanAnggaran->realisasi  ?>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: center">Serapan</th>
                    <td><?= $serapanAnggaran->getSerapan(); ?> %</td>
                </tr>
                <tr>
                    <th style="text-align: center">Potongan</th>
                    <td style="font-weight: bold;"><?= $serapanAnggaran->getPotongan(); ?> %</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
