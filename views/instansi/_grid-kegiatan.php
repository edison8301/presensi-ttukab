<?php

use app\components\Helper;
use app\models\Kegiatan;
use app\models\KegiatanSearch;
use yii\grid\GridView;
use yii\helpers\Html;

$searchModel = new KegiatanSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'No',
            'headerOptions' => ['style' => 'text-align:center;width:50px;'],
            'contentOptions' => ['style' => 'text-align:center;']
        ],
        [
            'attribute' => 'nama',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:left;'],
        ],
        [
            'attribute' => 'tanggal',
            'format' => 'raw',
            'value' => function (Kegiatan $data) {
                return Helper::getTanggal($data->tanggal);
            },
            'headerOptions' => ['style' => 'text-align:center;width:150px;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'format'=>'raw',
            'label' => 'Jumlah<br/>Pegawai',
            'encodeLabel' => false,
            'value' => function (Kegiatan $data) use ($instansi) {
                $jumlahPegawai = $instansi->getJumlahPegawai([
                    'tanggal' => $data->tanggal,
                ]);

                return Helper::rp($jumlahPegawai);
            },
            'headerOptions'=>['style'=>'text-align:center;width:120px;'],
            'contentOptions'=>['style'=>'text-align:center;'],
        ],
        [
            'format' => 'raw',
            'label' => 'Jumlah<br/>Hadir',
            'encodeLabel' => false,
            'value' => function (Kegiatan $data) use ($instansi) {
                $jumlahHadir = $instansi->getJumlahPegawaiHadirKegiatan([
                    'id_kegiatan' => $data->id,
                ]);

                return Helper::rp($jumlahHadir);
            },
            'headerOptions' => ['style' => 'text-align:center;width:120px;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'format' => 'raw',
            'label' => 'Jumlah<br/>Tidak Hadir',
            'encodeLabel' => false,
            'value' => function (Kegiatan $data) use ($instansi) {
                $jumlahPegawai = $instansi->getJumlahPegawai([
                    'tanggal' => $data->tanggal,
                ]);

                $jumlahHadir = $instansi->getJumlahPegawaiHadirKegiatan([
                    'id_kegiatan' => $data->id,
                ]);

                return Helper::rp($jumlahPegawai - $jumlahHadir, 0);
            },
            'headerOptions' => ['style' => 'text-align:center;width:120px;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'format' => 'raw',
            'value' => function (Kegiatan $data) use ($instansi) {
                $btn = null;
                $btn .= $data->getLinkExportExcelButton([
                    'id_instansi' => $instansi->id,
                ]);
                $btn .= ' ' . $data->getLinkExporPdfButton([
                    'id_instansi' => $instansi->id,
                ]);

                return $btn;
            },
            'headerOptions' => ['style' => 'text-align:center;width:130px;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
    ],
]); ?>