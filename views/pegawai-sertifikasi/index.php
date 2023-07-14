<?php

use app\models\Instansi;
use app\models\PegawaiSertifikasi;
use app\models\PegawaiSertifikasiJenis;
use yii\helpers\Html;
use yii\grid\GridView;

use app\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSertifikasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Sertifikasi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-sertifikasi-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pegawai Sertifikasi', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Pegawai Sertifikasi', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


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
                'attribute' => 'nama_pegawai',
                'format' => 'raw',
                'value' => function (PegawaiSertifikasi $data) {
                    return @$data->pegawai->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nip_pegawai',
                'format' => 'raw',
                'value' => function (PegawaiSertifikasi $data) {
                    return @$data->pegawai->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_pegawai_sertifikasi_jenis',
                'format' => 'raw',
                'value' => function ($data)
                {
                    return @$data->pegawaiSertifikasiJenis->nama;
                },
                'filter' => PegawaiSertifikasiJenis::findArrayDropDownList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function (PegawaiSertifikasi $data) {
                    return @$data->instansi->nama;
                },
                'filter' => Instansi::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function ($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function ($data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
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
