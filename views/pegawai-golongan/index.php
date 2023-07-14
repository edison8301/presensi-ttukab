<?php

use app\components\Helper;
use app\models\PegawaiGolongan;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiGolonganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Golongan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-golongan-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-download"></i> Import From Excel', ['import'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => function (PegawaiGolongan $data) {
                    return @$data->pegawai->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_golongan',
                'format' => 'raw',
                'value' => function (PegawaiGolongan $data) {
                    return @$data->golongan->golongan;
                },
                'headerOptions' => ['style' => 'text-align:center;width:100px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'format' => 'raw',
                'value' => function (PegawaiGolongan $data) {
                    return Helper::getTanggal($data->tanggal_berlaku);
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function (PegawaiGolongan $data) {
                    return Helper::getTanggal($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function (PegawaiGolongan $data) {
                    return $data->getLabelTanggalSelesai();
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
