<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use yii\helpers\Url;
use app\modules\absensi\models\KetidakhadiranKegiatan;
use app\modules\absensi\models\KetidakhadiranKegiatanStatus;
use app\modules\absensi\models\KetidakhadiranKegiatanKeterangan;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KetidakhadiranKegiatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $id_ketidakhadiran_kegiatan_jenis int */

$this->title = 'Daftar Ketidakhadiran '.($searchModel->ketidakhadiranKegiatanJenis ? $searchModel->ketidakhadiranKegiatanJenis->nama : "Kegiatan");
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search-index',[
        'ketidakhadiranKegiatanSearch'=>$searchModel,
        'action'=>Url::to(['index'])
]); ?>

<div class="ketidakhadiran-kegiatan-index box box-primary">

    <div class="box-header">
        <?php if(KetidakhadiranKegiatan::accessCreate(['id_ketidakhadiran_kegiatan_jenis'=>$id_ketidakhadiran_kegiatan_jenis])) { ?>
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Ketidakhadiran Kegiatan', ['create','id_ketidakhadiran_kegiatan_jenis'=>$searchModel->id_ketidakhadiran_kegiatan_jenis], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>

        <?= Html::a('<i class="fa fa-print"></i> Export Excel Ketidakhadiran Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>

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
                'value'=>function($data) {
                    $output = '';
                    $output .= @$data->pegawai->nama.'<br>';
                    $output .= @$data->pegawai->nip;
                    return $output;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal);
                },
                'headerOptions' => ['style' => 'text-align:center; width:120px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_ketidakhadiran_kegiatan_keterangan',
                'format' => 'raw',
                'value'=>function($data) {
                    return @$data->ketidakhadiranKegiatanKeterangan->nama;
                },
                'filter'=>KetidakhadiranKegiatanKeterangan::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute'=>'nama_instansi',
                'header' => 'Unit Kerja',
                'format' => 'raw',
                'value'=>function($data) {
                    return @$data->pegawai->getNamaInstansi();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left; width:200px'],
            ],
            [
                'attribute' => 'id_ketidakhadiran_kegiatan_status',
                'format' => 'raw',
                'value'=>function($data) {
                    return $data->getLabelIdKetidakhadiranKegiatanStatus();
                },
                'filter'=>KetidakhadiranKegiatanStatus::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'header' => 'Liburan',
                'format' => 'boolean',
                'value' => function (KetidakhadiranKegiatan $data) {
                    return $data->getIsLiburan();
                },
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function($data) {
                    $output  = '';

                    $output .= Html::a('<i class="fa fa-eye"></i>',['/absensi/ketidakhadiran-kegiatan/view','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';

                    if($data->accessUpdate()) {
                        $output .= Html::a('<i class="fa fa-pencil"></i>',['/absensi/ketidakhadiran-kegiatan/update','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Sunting']).' ';
                    }

                    if($data->accessDelete()) {
                        $output .= Html::a('<i class="fa fa-trash"></i>',['/absensi/ketidakhadiran-kegiatan/delete','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Hapus','onclick'=>'return confirm("Yakin akan menghapus data?");']);
                    }

                    return trim($output);
                },
                'headerOptions'=>['style'=>'text-align:center;width:60px;vertical-align:middle'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
            */
        ],
    ]); ?>
    </div>
</div>
