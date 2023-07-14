<?php

use app\components\Helper;
use app\models\InstansiPegawai;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Bawahan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-pegawai-index box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai Bawahan</h3>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
        ],
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
                'value'=>function(InstansiPegawai $data) {
                    return @$data->pegawai->nama.'<br/>'.@$data->pegawai->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nama_jabatan',
                'format' => 'raw',
                'value' => function(InstansiPegawai $data) {
                    return @$data->getNamaJabatan();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_jabatan_induk',
                'label' => 'Jabatan Atasan',
                'format' => 'raw',
                'value' => function(InstansiPegawai $data) {
                    return @$data->jabatanAtasan->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'label'=>'Tanggal<br/>TMT',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_berlaku);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label'=>'Tanggal<br/>Mulai Efektif',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'label'=>'Tanggal<br/>Selesai Efektif',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
        ],
    ]); ?>
    </div>
</div>
