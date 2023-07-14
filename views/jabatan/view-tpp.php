<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Jabatan */

$this->title = "Detail Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Jabatan</h3>
    </div>

	<div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template'=>'<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
    		[
            	'attribute'=>'nama',
            	'format'=>'raw',
                'value'=>$model->nama
            ],
            [
                'attribute'=>'status_kepala',
                'label' => 'Kepala UKE',
                'format'=>'raw',
                'value'=>$model->getNamaStatusKepala()
            ],
            [
                'label'=>'Instansi',
                'format'=>'raw',
                'value'=>@$model->instansi->nama
            ],
            [
                'label'=>'Jenis Jabatan',
                'format'=>'raw',
                'value'=>@$model->getJenisJabatan()
            ],
            [
                'label'=>'Tingkatan Fungsional',
                'format'=>'raw',
                'value'=>@$model->tingkatanFungsional->nama
            ],
            [
                'label'=>'Atasan',
                'format'=>'raw',
                'value'=>@$model->jabatanInduk->namaJnamaabatan
            ],
            [
                'label'=>'Nilai Jabatan',
                'format'=>'raw',
                'value'=>@$model->nilai_jabatan
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Jabatan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?= $model->getLinkUpdateAtasanKepala(); ?>
    </div>

</div>


<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"></h3>
    </div>
    <div class="box-body">
        <table class="table">
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">Pegawai</th>
                <th style="text-align: center">NIP</th>
                <th style="text-align: center">Golongan</th>
                <th style="text-align: center">Besar TPP</th>
            </tr>
            <?php
                $allInstansiPegawai = \app\models\InstansiPegawai::query([
                    'id_jabatan' => $model->id
                ])->all();
            ?>
            <?php $i=1; foreach ($allInstansiPegawai as $instansiPegawai) { ?>
                <?php
                    $pegawai = \app\modules\tukin\models\Pegawai::findOne($instansiPegawai->id_pegawai);
                ?>
            <tr>
                <td style="text-align: center"><?= $i; ?></td>
                <td><?= @$pegawai->nama; ?></td>
                <td style="text-align: center">
                    <?= @$pegawai->nip; ?>
                </td>
                <td style="text-align: center">
                    <?= $pegawai->getJabatanTunjanganGolongan(); ?>
                </td>
                <td style="text-align: right">
                    <?= Helper::rp($pegawai->getTppAwal(6),0); ?>
                </td>
            </tr>
            <?php $i++; } ?>
        </table>
    </div>
</div>
