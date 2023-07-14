<?php

use app\components\Helper;
use app\modules\absensi\models\PegawaiAbsensiManual;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiAbsensiManualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Absensi Manual';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-absensi-manual-index box box-primary">

    <div class="box-header">
        <?= $this->render('_modal-pegawai'); ?>
        <?php /*
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai Absensi Manual', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        */ ?>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center;width:10px;'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama_pegawai',
                'label' => 'Pegawai',
                'format' => 'raw',
                'value' => function(PegawaiAbsensiManual $data) {
                    return $data->pegawai->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nip_pegawai',
                'label' => 'NIP',
                'format' => 'raw',
                'value' => function(PegawaiAbsensiManual $data) {
                    return $data->pegawai->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;width:180px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->tanggal_selesai == null OR $data->tanggal_selesai == '9999-12-31') {
                        return Html::tag('label', 'Masih Berlaku', ['class' => 'label label-success']);
                    }

                    return Helper::getTanggal($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center;width:180px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'status',
                'label' => 'Absensi<br>Manual',
                'format' => 'raw',
                'encodeLabel' => false,
                'filter' => PegawaiAbsensiManual::getListStatus(),
                'value' => function(PegawaiAbsensiManual $data) {
                    return $data->getLabelStatus();
                },
                'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'text-align:center;width:80px;'],
                'contentOptions' => ['style' => 'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
