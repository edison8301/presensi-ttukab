<?php

use app\modules\absensi\models\KetidakhadiranJamKerja;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;
use app\modules\absensi\models\KetidakhadiranJamKerjaJenis;
use app\modules\absensi\models\KetidakhadiranJamKerjaStatus;
use app\models\Instansi;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\KetidakhadiranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Ketidakhadiran Jam Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search',[
    'searchModel'=>$searchModel,
    'action'=>['index']
]); ?>

<div class="ketidakhadiran-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Ketidakhadiran', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="table-responsive">
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
                        return Html::a(Html::encode(@$data->pegawai->nama),['/absensi/pegawai/view','id'=>@$data->pegawai->id]).'<br>NIP. '.Html::encode(@$data->pegawai->nip);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width:200px'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'tanggal',
                    'format' => 'raw',
                    'value'=>function($data) {
                        return Helper::getTanggalSingkat($data->tanggal);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'id_jam_kerja',
                    'format' => 'raw',
                    'value'=>function($data) {
                        return $data->jamKerja ? $data->jamKerja->nama : "";
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'id_ketidakhadiran_jam_kerja_jenis',
                    'format' => 'raw',
                    'value'=>function($data) {
                        return @$data->ketidakhadiranJamKerjaJenis->nama;
                    },
                    'filter'=>KetidakhadiranJamKerjaJenis::getList(),
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'keterangan',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'id_unit_kerja',
                    'header' => 'Perangkat Daerah',
                    'format' => 'raw',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'id_unit_kerja',
                        'data' => Instansi::getList(),
                        'options' => [
                            'placeholder' => 'Perangkat Daerah',
                        ],
                        'pluginOptions' => ['allowClear' => true],
                    ]),
                    'value'=>function(KetidakhadiranJamKerja $data) {
                        return @$data->pegawai !== null ? @$data->instansiPegawai->instansi->nama : null;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left; width:200px'],
                ],
                [
                    'attribute' => 'id_ketidakhadiran_jam_kerja_status',
                    'format' => 'raw',
                    'value'=>function($data) {
                        return $data->getLabelIdKetidakhadiranJamKerjaStatus();
                    },
                    'filter'=>KetidakhadiranJamKerjaStatus::getList(),
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'format'=>'raw',
                    'value'=>function($data) {
                        $output = '';

                        $output .= Html::a('<i class="fa fa-eye"></i>',['view','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Lihat Pengajuan']).' ';

                        if($data->accessSetSetuju()) {
                            $output .= Html::a('<i class="fa fa-check"></i>',['set-setuju','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Setujui Pengajuan']).' ';
                        }

                        if($data->accessSetTolak()) {
                            $output .= Html::a('<i class="fa fa-remove"></i>',['set-tolak','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Tolak Pengajuan']).' ';
                        }

                        if($data->accessUpdate()) {
                            $output .= Html::a('<i class="fa fa-pencil"></i>',['update','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Ubah Pengajuan']).' ';
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
        ]); ?>
    </div>
    </div>
</div>
