<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\components\Helper;
use app\models\Instansi;
use app\modules\absensi\models\Absensi;

/* @var $this yii\web\View */
/* @var $pegawaiSearch app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Absensi Pegawai : '.$pegawaiSearch->getMasa();

if($pegawaiSearch->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->params['breadcrumbs'][] = $this->title;


?>

<?= $this->render('_search-index',[
        'searchModel'=>$pegawaiSearch,
        'action'=>Url::to(['index'])
]); ?>

<div class="pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Rekap Absensi', Yii::$app->request->url.'&refresh=1', ['class' => 'btn btn-primary btn-flat','onclick'=>'return confirm("Yakin akan merefresh rekap absensi? Proses refresh akan memakan waktu beberapa menit")']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Rekap Absensi', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Rekap Absensi', Yii::$app->request->url.'&export-pdf=1', ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
    </div>

    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $pegawaiSearch,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        //'perfectScrollbar'=>true,
        'responsiveWrap' => true,
        'hover'=>true,
        'striped'=>false,
        //'tableOptions'=>['class'=>'table-responsive'],
        'responsive'=>true,
        //'floatHeader'=>true,
        //'perfectScrollbar'=>true,
        //'floatOverflowContainer'=>true,
        'floatHeaderOptions'=>['scrollingTop'=>'0'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center; width:60px;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function ($data) {

                    return Html::a(Html::encode($data->nama),['/absensi/pegawai/view','id'=>$data->id]).'<br>NIP. '.$data->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'label' => 'Unit Kerja',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getNamaInstansi();
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle; width: 250px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            /*
            [
                'label'=>'Jumlah<br>Potongan<br>(%)',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->getPotonganBulan($searchModel->bulan).' %';
                },
                'headerOptions' => ['style'=>'text-align:center;width:100px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'Hari<br>Kerja',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return '<span class="label label-primary">'.$data->_hari_kerja.'</span>';
                },
                'headerOptions' => ['style'=>'text-align:center;width:80px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'I',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_IZIN];
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'S',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_SAKIT];
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'C',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_CUTI];
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'DL',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_DINAS_LUAR];
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'TB',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_BELAJAR];
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'TD',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return $data->_hari_ketidakhadiran[Absensi::KETIDAKHADIRAN_TUGAS_KEDINASAN];
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            [
                'label'=>'TK',
                'encodeLabel'=>false,
                'format'=>'raw',
                'value'=>function($data) use ($searchModel) {
                    return '<span class="label label-danger">'.$data->_hari_tanpa_keterangan.'</span>';
                },
                'headerOptions' => ['style'=>'text-align:center;width:40px;vertical-align:middle'],
                'contentOptions' => ['style'=>'text-align:center;'],
            ],
            */
            [
                'format'=>'raw',
                'value'=>function($data) {
                    $output  = '';
                    $output .= Html::a('<i class="fa fa-eye"></i>',['view','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Lihat Absensi']).' ';
                    return trim($output);
                },
                'headerOptions'=>['style'=>'text-align:center;width:50px;vertical-align:middle'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ]
        ],
    ]); ?>
    </div>
</div>
