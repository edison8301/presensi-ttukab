<?php

use app\components\Helper;
use app\models\User;
use app\modules\absensi\models\KetidakhadiranPanjang;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\KetidakhadiranPanjang */

$this->title = "Detail Ketidakhadiran Panjang";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Panjang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-panjang-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Ketidakhadiran Panjang</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
            [
                'attribute' => 'tanggal_mulai',
                'value' => Helper::getTanggal($model->tanggal_mulai),
            ],
            [
                'attribute' => 'tanggal_selesai',
                'value' => Helper::getTanggal($model->tanggal_selesai)
            ],
            'keterangan',
            [
                'attribute' => 'id_ketidakhadiran_panjang_status',
                'format' => 'raw',
                'value' => @$model->ketidakhadiranPanjangStatus->getLabelNama(),
            ],
            [
                'attribute' => 'id_user_pembuat',
                'value' => @$model->userPembuat->username,
            ],
            [
                'attribute' => 'waktu_dibuat',
                'value' => Helper::getWaktuWIB($model->waktu_dibuat),
            ],
            [
                'attribute' => 'id_user_penyunting',
                'value' => @$model->userPenyunting->username,
            ],
            [
                'attribute' => 'waktu_disunting',
                'value' => Helper::getWaktuWIB($model->waktu_disunting),
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?php if ($model->accessUpdate()): ?>
            <?= Html::a('<i class="fa fa-pencil"></i> Sunting Ketidakhadiran Panjang', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif ?>
        <?php if (User::isAdmin() or User::isVerifikator() or User::isInstansi()): ?>
            <?= Html::a('<i class="fa fa-list"></i> Daftar Ketidakhadiran Panjang', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
        <?php endif ?>

        <?php if (User::isAdmin() or User::isVerifikator()): ?>
            <?= Html::a('<i class="fa fa-check"></i> Setujui Ketidakhadiran', ['set-setuju', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']); ?>
            <?= Html::a('<i class="fa fa-times"></i> Tolak Ketidakhadiran', ['set-tolak', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat']); ?>
        <?php endif ?>
    </div>

</div>
