<?php

use app\components\Helper;
use app\models\PegawaiNonTpp;
use app\models\PegawaiNonTppJenis;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiNonTppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Non Tpp';

if ($searchModel->id_pegawai_non_tpp_jenis == PegawaiNonTppJenis::CUTI_BESAR) {
    $this->title = 'Daftar Pegawai Cuti Besar Non TPP';
}

if ($searchModel->id_pegawai_non_tpp_jenis == PegawaiNonTppJenis::TUGAS_BELAJAR) {
    $this->title = 'Daftar Pegawai Tugas Belajar Non TPP';
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-non-tpp-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data', [
            'create',
            'id_pegawai_non_tpp_jenis' => $searchModel->id_pegawai_non_tpp_jenis
        ], ['class' => 'btn btn-success btn-flat']) ?>

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
                'headerOptions' => ['style' => 'text-align:center;width:50px;'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama_pegawai',
                'label' => 'Pegawai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
                'value' => function (PegawaiNonTpp $data) {
                    return @$data->pegawai->nama;
                },
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (PegawaiNonTpp $data) {
                    return Helper::getTanggal($data->tanggal_mulai);
                },
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (PegawaiNonTpp $data) {
                    return Helper::getTanggal($data->tanggal_selesai);
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
