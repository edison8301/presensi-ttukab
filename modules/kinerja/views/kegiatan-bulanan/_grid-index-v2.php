<?php

use app\modules\kinerja\models\InstansiPegawaiSkp;
use app\modules\kinerja\models\KegiatanBulanan;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\Helper;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'striped'=>false,
    'hover'=>true,
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => 'No',
            'headerOptions' => ['style' => 'text-align:center'],
            'contentOptions' => ['style' => 'text-align:center']
        ],
        [
            'attribute' => 'nama_kegiatan',
            'format' => 'raw',
            'label'=>'Kinerja Bulanan',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:left;'],
            'value' => function (KegiatanBulanan $data) {
                $output = Html::a($data->kegiatanTahunan->nama,['kegiatan-bulanan/view-v2','id'=>$data->id]).'<br>';
                $output .= '<i class="fa fa-user"></i> ';
                $output .= $data->kegiatanTahunan ? $data->kegiatanTahunan->namaPegawai : '';

                return $output;
            },
            'pageSummary' => 'Capaian Kinerja (%)',
            'pageSummaryOptions' => ['style' => 'text-align:left;font-weight:bold'],
        ],
        [
            'attribute' => 'nomor_skp',
            'label' => 'Nomor<br/>SKP',
            'encodeLabel' => false,
            'value' => function(KegiatanBulanan $data) {
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
            'value' => function(KegiatanBulanan $data) {
                return @$data->instansiPegawaiSkp->nomor;
            },
            'visible'=>($searchModel->id_pegawai==null),
            'headerOptions' => ['style' => 'text-align:center;width:83px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'bulan',
            'format' => 'raw',
            'filter'=>Helper::getListBulanSingkat(),
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'value' => function (KegiatanBulanan $data) {
                return $data->getNamaBulanSingkat();
            }
        ],
        [
            'label'=>'Jenis',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->kegiatanTahunan ? $data->kegiatanTahunan->getTextInduk() : '';
            },
            'headerOptions' => ['style' => 'text-align:center;width:100px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=>function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_aspek', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true
        ],
        /* [
            'attribute' => 'target',
            'label'=>'Target',
            'encodeLabel'=>false,
            'format' => 'raw',
            'value' => function(KegiatanBulanan $data) {
                return $data->target.' '.$data->getSatuanKuantitas();
            },
            'filter'=>'',
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute'=>'realisasi',
            'label'=>'Realisasi',
            'encodeLabel'=>false,
            'format' => 'raw',
            'value' => function(KegiatanBulanan $data) {
                return Helper::rp($data->kegiatanTahunan->getTotalRealisasi(['bulan'=>$data->bulan]),0).' '.$data->getSatuanKuantitas();
            },
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ], */
        [
            'attribute'=>'realisasi',
            'label'=>'%',
            'format' => 'raw',
            'value' => function(KegiatanBulanan $data) {
                return $data->persen_realisasi;
                //return number_format($data->getPersenRealisasi(),2);
            },
            'headerOptions' => ['style' => 'text-align:center;width:80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'pageSummary' =>true,
            'pageSummaryFunc' => GridView::F_AVG,
        ],
        [
            'label'=>'Status',
            'format'=>'raw',
            'value' => function(KegiatanBulanan $data) {
                return $data->kegiatanTahunan->labelIdKegiatanStatus;
            },
            'headerOptions' => ['style' => 'text-align:center;width:50px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'format'=>'raw',
            'value'=>function(KegiatanBulanan $data) use ($searchModel) {
                $output = '';

                $output .= Html::a('<i class="fa fa-eye"></i>',['kegiatan-bulanan/view-v2','id'=>$data->id,'mode'=>$searchModel->mode],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';
                $output .= Html::a('<i class="fa fa-refresh"></i>',['kegiatan-bulanan/update-realisasi','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Perbarui Realisasi']).' ';

                return trim($output);
            },
            'headerOptions' => ['style' => 'text-align:center;width:60px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ]
    ],
    'showPageSummary' => true,
    'pageSummaryRowOptions' => ['style' => 'text-align:center;']
]);
