<?php

use app\components\Helper;
use yii\bootstrap\Tabs;
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
                'label' => 'Unit Kerja',
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
        <?= Html::a('<i class="fa fa-list"></i> Daftar SKP', ['index-v2'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF', ['instansi-pegawai-skp/export-pdf-skp-v2', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat','target' => '_blank']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF FORM II JF', ['instansi-pegawai-skp/export-pdf-form-ii-jf', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat','target' => '_blank']) ?>
        <?php /* Html::a('<i class="fa fa-print"></i> Export Excel', ['instansi-pegawai-skp/export-excel-skp', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) */ ?>
    </div>

</div>

<div class="box box-primary">
    <div class="box-body">

        <?= Tabs::widget([
            'headerOptions' => [
                'style'=>'font-weight:bold;',
            ],
            'tabContentOptions' => [
                'style'=>'padding-top:20px',
                'class'=>''
            ],
            'items' => [
                [
                    'label' => 'SKP',
                    'content' => $this->render('_tab-form-skp',[
                        'model' => $model
                    ]),
                ],
                [
                    'label' => 'Form II JF',
                    'content' => $this->render('_tab-form-ii-jf',[
                        'model'=>$model
                    ]),
                ],
            ],
        ]); ?>
        
    </div>
</div>
