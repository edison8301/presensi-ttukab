<?php

use app\components\Helper;
use app\models\Instansi;
use app\modules\absensi\models\KetidakhadiranPanjang;
use app\modules\absensi\models\KetidakhadiranPanjangJenis;
use app\modules\absensi\models\KetidakhadiranPanjangSearch;
use app\modules\absensi\models\KetidakhadiranPanjangStatus;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/* @see \app\modules\absensi\controllers\KetidakhadiranPanjangController::actionIndex() */
/* @var $this yii\web\View */
/* @var $searchModel KetidakhadiranPanjangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Ketidakhadiran Hari Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search', [
    'searchModel' => $searchModel,
    'action' => Url::current(),
]); ?>

<div class="ketidakhadiran-panjang-index box box-primary">

    <div class="box-header">
        <?=Html::a('<i class="fa fa-plus"></i> Tambah Ketidakhadiran Hari Kerja', ['create'], ['class' => 'btn btn-success btn-flat'])?>
        <?=Html::a('<i class="fa fa-print"></i> Export Excel Ketidakhadiran Hari Kerja', Yii::$app->request->url . '&export=1', ['class' => 'btn btn-success btn-flat'])?>
    </div>

    <div class="box-body">


    <div class="table-responsive">
        <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'firstPageLabel' => 'First',
                'lastPageLabel'  => 'Last'
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center; width:60px'],
                    'contentOptions' => ['style' => 'text-align:center'],
                ],
                [
                    'attribute' => 'nama_pegawai',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return @$data->pegawai->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'id_ketidakhadiran_panjang_jenis',
                    'format' => 'raw',
                    'filter' => KetidakhadiranPanjangJenis::getList(),
                    'value' => function ($data) {
                        return @$data->ketidakhadiranPanjangJenis->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:130px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'tanggal_mulai',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Helper::getTanggalSingkat($data->tanggal_mulai);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'tanggal_selesai',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Helper::getTanggalSingkat($data->tanggal_selesai);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'id_unit_kerja',
                    'header' => 'Unit Kerja',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'id_unit_kerja',
                        'data' => Instansi::getList(),
                        'options' => [
                            'placeholder' => 'Unit Kerja',
                        ],
                        'pluginOptions' => ['allowClear' => true],
                    ]),
                    'value' => function (KetidakhadiranPanjang $data) {
                        return @$data->instansiPegawai->instansi->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left; width:200px'],
                ],
                [
                    'attribute' => 'keterangan',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return '<span data-toggle="tooltip" title="' . Html::encode($data->keterangan) . '">' .
                            StringHelper::truncate(Html::encode($data->keterangan), 50) .
                            '</span>';
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:150px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'id_ketidakhadiran_panjang_status',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return @$data->ketidakhadiranPanjangStatus->getLabelNama();
                    },
                    'filter' => KetidakhadiranPanjangStatus::getList(),
                    'headerOptions' => ['style' => 'text-align:center; width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'format' => 'raw',
                    'value' => function (KetidakhadiranPanjang $data) {
                        $output = '';

                        $output .= Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Pengajuan']) . ' ';

                        if ($data->accessSetSetuju()) {
                            $output .= Html::a('<i class="fa fa-check"></i>', ['set-setuju', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Setujui Pengajuan']) . ' ';
                        }

                        if ($data->accessSetTolak()) {
                            $output .= Html::a('<i class="fa fa-remove"></i>', ['set-tolak', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Tolak Pengajuan']) . ' ';
                        }

                        if ($data->accessUpdate()) {
                            $output .= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Pengajuan']) . ' ';
                        }

                        if ($data->accessDelete()) {
                            $output .= Html::a('<i class="fa fa-trash"></i>', ['delete', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Hapus Pengajuan', 'data-confirm'=>'Yakin akan menghapus data?']) . ' ';
                        }

                        return trim($output);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:80px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
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
</div>
