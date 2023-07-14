<?php

use app\components\Helper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $pegawaiRekapAbsensiSearch app\modules\absensi\models\PegawaiRekapAbsensiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Rekap Absensi';
$this->params['breadcrumbs'][] = $this->title;
?>

<?=$this->render('_search-index', [
    'pegawaiRekapAbsensiSearch' => $pegawaiRekapAbsensiSearch,
    'action' => Url::to(['izin']),
]);?>

<div class="pegawai-rekap-absensi-index box box-primary">
    <div class="box-header">
        <?=Html::a('<i class="fa fa-refresh"></i> Refresh Rekap Absensi', Yii::$app->request->url . '&refresh=1', ['class' => 'btn btn-primary btn-flat', 'onclick' => 'return confirm("Yakin akan merefresh rekap absensi? Proses refresh akan memakan waktu beberapa menit")'])?>
        <?=Html::a('<i class="fa fa-print"></i> Export Excel Rekap Absensi', Yii::$app->request->url . '&export=1', ['class' => 'btn btn-primary btn-flat'])?>
        <?=Html::a('<i class="fa fa-print"></i> Export PDF Rekap Absensi', Yii::$app->request->url . '&export-pdf=1', ['class' => 'btn btn-primary btn-flat', 'target' => '_blank'])?>
    </div>
    <div class="box-body table-responsive">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $pegawaiRekapAbsensiSearch,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => 'No',
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center'],
            ],
            [
                'attribute' => 'nama_pegawai',
                'format' => 'raw',
                'value' => function ($data) {
                    $output = Html::a(@$data->pegawai->nama, ['/absensi/pegawai/view', 'id' => $data->id_pegawai]) . '<br>';
                    $output .= @$data->pegawai->nip;
                    return $output;
                },
                'hAlign' => 'left',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => function ($data) {
                    return Helper::getBulanSingkat($data->bulan) . '<br>' . $data->tahun;
                },
                'headerOptions' => ['style' => 'text-align:center;width:60px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jumlah_hari_kerja',
                'label' => 'Jumlah<br>Hari<br>Kerja',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jumlah_hadir',
                'label' => 'Jumlah<br>Hadir',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jumlah_tidak_hadir',
                'label' => 'Jumlah<br>Tidak<br>Hadir',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jumlah_izin',
                'label' => 'Jumlah<br>Izin',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;width:80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format' => 'raw',
                'value' => function ($data) {
                    $output = Html::a('<i class="fa fa-eye"></i>', ['/absensi/pegawai/view', 'id' => $data->id_pegawai], ['data-toggle' => 'tooltip', 'title' => 'Lihat Rekap']) . ' ';
                    $output .= Html::a('<i class="fa fa-refresh"></i>', ['/absensi/pegawai/refresh-pegawai-rekap-absensi', 'id' => $data->id_pegawai], ['data-toggle' => 'tooltip', 'title' => 'Refresh Rekap']) . ' ';

                    return trim($output);
                },
                'headerOptions' => ['style' => 'text-align:center; width:60px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ]
            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
            */
        ],
    ]);?>
    </div>
</div>
