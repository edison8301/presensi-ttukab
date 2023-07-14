<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\components\Helper;
use app\models\InstansiPegawai;
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Daftar Pegawai';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_filter', [
    'action' => ['/tunjangan/instansi-pegawai/index-rekap-pegawai'],
    'searchModel' => $searchModel,
]) ?>

<div class="box box-primary">
    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Rekap', [
            '/tunjangan/instansi-pegawai/refresh-rekap-pegawai-bulan',
            'id_instansi' => $searchModel->id_instansi,
            'bulan' => $searchModel->bulan,
        ], [
            'class' => 'btn btn-primary btn-flat',
            'onclick' => 'return confirm("Yakin akan merefresh rekap? Proses refresh mungkin akan memakan waktu beberapa menit")'
        ]) ?>

        <?= Html::a('<i class="fa fa-print"></i> Export Excel Rekap', Yii::$app->request->url . '&export-excel-rekap-pegawai=1', [
            'class' => 'btn btn-success btn-flat',
        ]) ?>
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
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;width:10px;'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                [
                    'attribute' => 'id_instansi',
                    'label' => 'PD / Perangkat Daerah',
                    'format' => 'raw',
                    'value'=>function(InstansiPegawai $data) {
                        return @$data->instansi->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'nip_pegawai',
                    'label' => 'NIP',
                    'format' => 'raw',
                    'value'=>function(InstansiPegawai $data) {
                        return @$data->pegawai->nip;
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;width:150px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'nama_pegawai',
                    'label' => 'Nama',
                    'format' => 'raw',
                    'value'=>function(InstansiPegawai $data) {
                        return @$data->pegawai->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'id_golongan',
                    'label' => 'GOL',
                    'format' => 'raw',
                    'value'=>function(InstansiPegawai $data) use ($searchModel) {
                        return $data->pegawai->getNamaPegawaiGolonganBerlaku([
                            'bulan' => $searchModel->bulan
                        ]);                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;width:50px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'nama_jabatan',
                    'label' => 'Jabatan',
                    'format' => 'raw',
                    'value'=>function(InstansiPegawai $data) {
                        return $data->nama_jabatan;
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'kelas_jabatan',
                    'label' => 'Kelas<br>Jabatan',
                    'format' => 'raw',
                    'encodeLabel' => false,
                    'value'=>function(InstansiPegawai $data) {
                        return @$data->jabatan->kelas_jabatan;
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;width:50px;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'label' => 'Besaran TPP',
                    'format' => 'raw',
                    'encodeLabel' => false,
                    'value' => function(InstansiPegawai $data) use ($searchModel) {
                        return Helper::rp(@$data->pegawai->getRupiahTppKotorFromRekap([
                            'bulan' => $searchModel->bulan,
                        ]), 0);
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle;'],
                    'contentOptions' => ['style' => 'text-align:right;'],
                ],
            ],
        ]); ?>
    </div>
</div>
