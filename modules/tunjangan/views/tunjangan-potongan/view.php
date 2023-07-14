<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\TunjanganPotongan */

$this->title = "Detail Tunjangan Potongan";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Potongan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-potongan-view box box-primary">

    <div class="box-header">
        <h2 class="box-title">Detail Tunjangan Potongan</h2>
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
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Tunjangan Potongan', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Tunjangan Potongan', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>
</div>



<div class="tunjangan-potongan-view box box-primary">

    <div class="box-header">
        <h2 class="box-title">Detail Tunjangan Potongan</h2>
    </div>

    <div class="box-body">

        <?= Html::a('<i class="fa fa-plus"></i> Update Nilai Potongan', ['tunjangan-potongan-nilai/create', 'id_tunjangan_potongan' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <div>&nbsp;</div>

        <table class="table table-bordered">
            <tr>
                <th class="text-center" style="width: 10px">No</th>
                <th class="text-center" style="width: 100px">Jumlah Potongan</th>
                <th class="text-center">Tanggal Mulai Berlaku</th>
                <th class="text-center">Tanggal Selesai Berlaku</th>
                <th class="text-center">&nbsp;</th>
            </tr>
            <?php $no=1; foreach ($model->manyTunjanganPotonganNilai as $tunjanganPotonganNilai){ ?>    
            <tr>
                <td><?= $no++; ?></td>
                <td class="text-center"><?= number_format($tunjanganPotonganNilai->nilai,2); ?> %</td>
                <td class="text-center"><?= Helper::getTanggal($tunjanganPotonganNilai->tanggal_mulai); ?></td>
                <td class="text-center"><?= $tunjanganPotonganNilai->getLabelTanggalSelesai(); ?></td>
                <td class="text-center">
                    <?= Html::a("<i class='fa fa-pencil'></i>",['tunjangan-potongan-nilai/update','id' => $tunjanganPotonganNilai->id]); ?>&nbsp;
                    <?= Html::a("<i class='fa fa-trash'></i>",['tunjangan-potongan-nilai/delete','id' => $tunjanganPotonganNilai->id],['data-confirm' => 'Apa anda yakin untuk menghapus data ini?','data-method' => 'post']); ?>&nbsp;
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>