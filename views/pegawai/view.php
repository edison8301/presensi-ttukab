<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// $model->execCmdContentHapus();
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
                'attribute' => 'nik',
                'format' => 'raw',
                'value' => $model->nik,
            ],
            [
                'attribute' => 'gender',
                'format' => 'raw',
                'value' => $model->gender,
            ],
            [
                'attribute' => 'tempat_lahir',
                'format' => 'raw',
                'value' => $model->tempat_lahir,
            ],
            [
                'attribute' => 'tanggal_lahir',
                'format' => 'raw',
                'value' => $model->tanggal_lahir,
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
            [
                'label' => 'Golongan',
                'value' => @$model->pegawaiGolonganBerlaku->golongan->golongan
            ],
            [
                'attribute' => 'id_eselon',
                'value' => $model->getEselonJabatan(),
            ],
            [
                'label' => 'Kelas Jabatan',
                'value' => @$model->getKelasJabatan()
            ],
            [
                'label' => 'Pendidikan',
                'attribute' => 'id_pendidikan',
                'value' => @$model->pendidikan->nama
            ],
            /*
            [
                'attribute' => 'foto',
                'format' => 'raw',
                'value' => $model->foto,
            ],
            */
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php if(\app\components\Session::isAdmin()) { ?>
            <?= Html::a('<i class="fa fa-pencil"></i> Ubah Pegawai', [
                '/pegawai/update',
                'id' => $model->id,
            ], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php } ?>
    </div>

</div>

<?php // $this->render('_pegawai_mutasi', ['model' => $model]); ?>

<?= $this->render('_pegawai-instansi',['model' => $model]); ?>

<?= $this->render('_pegawai-golongan',['model' => $model]); ?>
