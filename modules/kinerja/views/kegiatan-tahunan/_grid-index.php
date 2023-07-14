<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanTahunan;
use kartik\grid\GridView;
use app\modules\kinerja\models\KegiatanStatus;
use yii\helpers\Html;
use app\components\Helper;
use app\models\User;

/* @var \app\modules\kinerja\models\KegiatanTahunanSearch $searchModel */

if(!isset($debug)) {
    $debug = false;
}

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'striped' => false,
    'hover' => true,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['style' => 'text-align:center; width: 40px'],
            'contentOptions' => ['style' => 'text-align:center'],
            'checkboxOptions' => function (KegiatanTahunan $model, $key, $index, $column) {
                if ($model->accessSetPeriksa() or User::isAdmin()) {
                    return ['value' => $key];
                }
                return ['style' => ['display' => 'none'], 'disabled' => true]; // OR ['disabled' => true]
            },
            'visible' => $searchModel->isScenarioPegawai()
        ],
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['style' => 'text-align:center; width: 40px'],
            'contentOptions' => ['style' => 'text-align:center'],
            'checkboxOptions' => function (KegiatanTahunan $model, $key, $index, $column) {
                if ($model->id_kegiatan_status == KegiatanStatus::PERIKSA) {
                    return ['value' => $key];
                }
                return ['style' => ['display' => 'none'], 'disabled' => true]; // OR ['disabled' => true]
            },
            'visible' => $searchModel->isScenarioAtasan()
        ],
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'No',
            'headerOptions' => ['style' => 'text-align:center; vertical-align:middle; width:50px'],
            'contentOptions' => ['style' => 'text-align:center']
        ],
        [
            'attribute' => 'nama',
            'format' => 'raw',
            'value' => function($data) {
                $output = Html::a($data->getEncodeNama(),['kegiatan-tahunan/view','id' => $data->id]).'<br>';
                $output .= '<i class="fa fa-user"></i> ';
                $output .= ucwords(strtolower(@$data->pegawai->getEncodeNama()));

                return $output . " - $data->tahun";
            },
            'headerOptions' => ['style' => 'text-align:center; vertical-align:middle;'],
            'contentOptions' => ['style' => 'text-align:left;'],
        ],
        [
            'attribute' => 'tahun',
            'headerOptions' => ['style' => 'text-align:center; vertical-align:middle; width:50px'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'visible' => 0
        ],
        [
            'attribute' => 'nomor_skp',
            'label' => 'Nomor<br/>SKP',
            'encodeLabel' => false,
            'value' => function(KegiatanTahunan $data) {
                return @$data->instansiPegawaiSkp->nomor;
            },
            'filter'=>InstansiPegawaiSkp::getList(['id_pegawai'=>$searchModel->id_pegawai]),
            'visible'=>($searchModel->id_pegawai!=null),
            'headerOptions' => ['style' => 'text-align:center;width:104px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'nomor_skp',
            'label' => 'Nomor<br/>SKP',
            'encodeLabel' => false,
            'value' => function(KegiatanTahunan $data) {
                return @$data->instansiPegawaiSkp->nomor;
            },
            'visible'=>($searchModel->id_pegawai==null),
            'headerOptions' => ['style' => 'text-align:center;width:83px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'target_angka_kredit',
            'label' => 'AK',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function ($data) {
                return $data->target_angka_kredit;
            },
            'headerOptions' => ['style' => 'text-align:center; vertical-align:middle; width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],

        ],
        [
            'attribute' => 'target_waktu',
            'label' => 'Target<br>Waktu',
            'encodeLabel' => false,
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'value' => function ($data) {
                return $data->target_waktu.' Bulan';
            }
        ],
        [
            'attribute' => 'target_biaya',
            'label' => 'Target<br>Biaya',
            'encodeLabel' => false,
            'format' => 'raw',
            'visible' => User::isPegawaiEselonII(),
            'value' => function($data) {
                return Helper::rp($data->target_biaya);
            },
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'target_kuantitas',
            'label' => 'Target<br>Kuantitas',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function($data) {
                return trim($data->target_kuantitas.' '.$data->satuan_kuantitas);
            },
            'headerOptions' => ['style' => 'text-align:center; vertical-align:middle; width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'realisasi_kuantitas',
            'label' => 'Realisasi<br>Kuantitas',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function(KegiatanTahunan $data) {
                $output = Html::a($data->getTotalRealisasi().' '.$data->satuan_kuantitas,[
                    '/kinerja/kegiatan-harian/index',
                    'KegiatanHarianSearch[id_kegiatan_tahunan]'=>$data->id
                ],[
                    'target' => '_blank'
                ]);

                return $output;
            },
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'label' => 'Jumlah<br>Tahapan',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function(KegiatanTahunan $data) {
                /* @var $data KegiatanTahunan */
                return count($data->manySub);
            },
            'visible' => true,
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'id_kegiatan_status',
            'label' => 'Status',
            'format' => 'raw',
            'value' => function(KegiatanTahunan $data) {
                return $data->kegiatanStatus->label;
            },
            'filter' => KegiatanStatus::getList(),
            'headerOptions' => ['style' => 'text-align:center; vertical-align:middle; width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'label' => 'Jumlah<br/>Keg. Bulanan',
            'encodeLabel' => false,
            'format' => 'raw',
            'value' => function($data) {
                return count($data->manyKegiatanBulanan);
            },
            'filter' => KegiatanStatus::getList(),
            'headerOptions' => ['style' => 'text-align:center;width:100px'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'visible' => (@$debug==true),
        ],
        [
            'label'=>'Aksi',
            'format' => 'raw',
            'value' => function (KegiatanTahunan $data) use ($searchModel) {
                $output = '';

                if($data->accessSetPeriksa()) {
                    $output .= Html::a('<i class="fa fa-send-o"></i>',['kegiatan-tahunan/set-periksa','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Periksa Kegiatan','onclick' => 'return confirm("Yakin akan mengirim kegiatan untuk diperiksa?");']).' ';
                }

                if($data->accessSetSetuju()) {
                    $output .= Html::a('<i class="fa fa-check"></i>',['kegiatan-tahunan/set-setuju','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Setuju Kegiatan','onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']).' ';
                }

                if($data->accessSetTolak()) {
                    $output .= Html::a('<i class="fa fa-remove"></i>',['kegiatan-tahunan/set-tolak','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Tolak Kegiatan','onclick' => 'return confirm("Yakin akan menolak kegiatan?");']).' ';
                }

                if($data->accessView()) {
                    $output .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['kegiatan-tahunan/view', 'id' => $data->id, 'mode' => $searchModel->mode], ['data-toggle' => 'tooltip', 'title' => 'Lihat']) . ' ';
                }

                if($data->accessUpdate()) {
                    $output .= Html::a('<i class="glyphicon glyphicon-pencil"></i>',['kegiatan-tahunan/update','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Ubah']).' ';
                }

                if($data->accessDelete()) {
                    $output .= Html::a('<i class="glyphicon glyphicon-trash"></i>',['kegiatan-tahunan/delete','id' => $data->id],['data-toggle' => 'tooltip','title' => 'Hapus','onclick' => 'return confirm("Yakin akan menghapus data?");']).' ';
                }

                return trim($output);
            },
            'headerOptions' => ['style' => 'text-align:center;width:100px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ]
        /*
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'text-align:center;width:80px']
        ],
        */
    ],
]);
