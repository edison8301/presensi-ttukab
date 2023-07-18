<?php

use app\components\Helper;
use app\models\Instansi;
use kartik\grid\GridView;
use yii\helpers\Html;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'resizableColumns'=>true,
    'floatHeader'=>false,
    'floatHeaderOptions'=>['scrollingTop'=>'0'],
    'pager' => [
        'firstPageLabel' => 'Awal',
        'lastPageLabel' => 'Akhir',
    ],
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header'=> 'No',
            'headerOptions' => ['style'=>'text-align:center;width:50px;'],
            'contentOptions' => ['style'=>'text-align:center']
        ],
        [
            'attribute'=>'nama',
            'format'=>'raw',
            'headerOptions'=>['style'=>'text-align:center;'],
            'contentOptions'=>['style'=>'text-align:left;'],
        ],
        [
            'format'=>'raw',
            'label' => 'Jumlah<br/>Pegawai',
            'encodeLabel' => false,
            'value' => function (Instansi $data) use ($kegiatan) {
                $jumlahPegawai = $data->getJumlahPegawai([
                    'tanggal' => $kegiatan->tanggal,
                ]);

                return Helper::rp($jumlahPegawai);
            },
            'headerOptions'=>['style'=>'text-align:center;width:120px;'],
            'contentOptions'=>['style'=>'text-align:center;'],
        ],
        [
            'format'=>'raw',
            'label' => 'Jumlah<br/>Hadir',
            'encodeLabel' => false,
            'value' => function (Instansi $data) use ($kegiatan) {
                $jumlahHadir = $data->getJumlahPegawaiHadirKegiatan([
                    'id_kegiatan' => $kegiatan->id,
                ]);

                return Helper::rp($jumlahHadir);
            },
            'headerOptions'=>['style'=>'text-align:center;width:120px;'],
            'contentOptions'=>['style'=>'text-align:center;'],
        ],
        [
            'format'=>'raw',
            'label' => 'Jumlah<br/>Tidak Hadir',
            'encodeLabel' => false,
            'value' => function (Instansi $data) use ($kegiatan) {
                $jumlahPegawai = $data->getJumlahPegawai([
                    'tanggal' => $kegiatan->tanggal,
                ]);

                $jumlahHadir = $data->getJumlahPegawaiHadirKegiatan([
                    'id_kegiatan' => $kegiatan->id,
                ]);

                return Helper::rp($jumlahPegawai - $jumlahHadir, 0);
            },
            'headerOptions'=>['style'=>'text-align:center;width:120px;'],
            'contentOptions'=>['style'=>'text-align:center;'],
        ],
        [
            'format'=>'raw',
            'encodeLabel' => false,
            'value' => function (Instansi $data) use ($kegiatan) {
                return Html::a('<i class="fa fa-file-excel-o"></i> Export', [
                    '/kegiatan/export-excel-rekap',
                    'id' => $kegiatan->id,
                    'id_instansi' => $data->id
                ], ['class' => 'btn btn-success btn-flat btn-xs']);
            },
            'headerOptions'=>['style'=>'text-align:center;width:80px;'],
            'contentOptions'=>['style'=>'text-align:center;'],
        ],
    ],
]); ?>
