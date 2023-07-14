<?php

use app\modules\kinerja\models\KegiatanHarian;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\Helper;
use app\modules\kinerja\models\Kinerja;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KegiatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Kegiatan Harian';
if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->title .= ' : '.$searchModel->getHariTanggal();


$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_filter-index',['searchModel'=>$searchModel]); ?>

<div class="box box-primary">
    <div class="box-header">
        <?= Html::beginForm(['/kinerja/kegiatan-harian/index'], 'post'); ?>
        <?= Html::a('<i class="fa fa-plus"></i> Kegiatan SKP', ['create','id_kegiatan_harian_jenis'=>1,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('<i class="fa fa-plus"></i> Kegiatan Tambahan', ['create','id_kegiatan_harian_jenis'=>2,'tanggal'=>$searchModel->tanggal], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php // eecho Html::a('<i class="fa fa-print"></i> Export Excel Kegiatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::submitButton('<i class="fa fa-send-o"></i> Kirim Kegiatan', [
                'class' => 'btn btn-warning btn-flat',
                'data-confirm' => 'Yakin akan kirim kegiatan yang dipilih?',
                'name' => 'aksi',
                'value' => 'yii1'
        ]); ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'headerOptions' => ['style' => 'text-align:center; width: 40px'],
                    'contentOptions' => ['style' => 'text-align:center'],
                    'checkboxOptions' => function (KegiatanHarian $model, $key, $index, $column) {
                        if ($model->accessSetPeriksa()) {
                            return ['value' => $key];
                        }
                        return ['style' => ['display' => 'none'], 'disabled' => true]; // OR ['disabled' => true]
                    },
                ],
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center; width: 40px'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                [
                    'attribute' => 'tanggal',
                    'format' => 'raw',
                    'filter'=>'',
                    'value'=>function(KegiatanHarian $data) {
                        return Helper::getHari($data->tanggal).'<br>'.Helper::getTanggalSingkat($data->tanggal);
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:90px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'uraian',
                    'format' => 'raw',
                    'value'=>function(KegiatanHarian $data) {
                        $output = Html::a(Html::encode($data->uraian),['kegiatan-harian/view','id'=>$data->id]).'<br>';
                        $output .= '<i class="fa fa-tags"></i> ';

                        if($data->id_kegiatan_harian_jenis==Kinerja::KEGIATAN_SKP) {
                            $output .= @$data->getNamaKegiatanTahunan().'<br>';
                        }

                        if($data->id_kegiatan_harian_jenis==Kinerja::KEGIATAN_TAMBAHAN) {
                            $output .= @$data->kegiatanHarianTambahan->nama.'<br>';
                        }

                        $output .= '<i class="fa fa-user"></i> ';
                        $output .= ucwords(strtolower(Html::encode(@$data->pegawai->nama)));

                        return $output;

                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute'=>'id_kegiatan_harian_jenis',
                    'header' => 'Jenis',
                    'value' => function ($data) {
                        return @$data->kegiatanHarianJenis->nama;
                    },
                    'filter'=>KegiatanHarianJenis::getList(),
                    'headerOptions' => ['style' => 'text-align:center;width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute'=>'waktu',
                    'header' => 'Waktu',
                    'value' => function (KegiatanHarian $data) {
                        return $data->getWaktu();
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'kuantitas',
                    'headerOptions' => ['style' => 'text-align:center; width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                    'value' => function (KegiatanHarian $data) {
                        return $data->getKuantitasSatuan();
                    }
                ],
                [
                    'attribute' => 'id_kegiatan_status',
                    'format' => 'raw',
                    'filter'=> KegiatanStatus::getList(),
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                    'value' => function (KegiatanHarian $data) {
                        return $data->kegiatanStatus ? $data->kegiatanStatus->getLabel() : null;
                    }
                ],
                [
                    'format'=>'raw',
                    'value'=>function(KegiatanHarian $data) use ($searchModel) {
                        $output = '';
                        if($data->accessSetPeriksa()) {
                            $output .= Html::a('<i class="fa fa-send-o"></i>',['kegiatan-harian/set-periksa','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Kirim Untuk Diperiksa','onclick'=>'return confirm("Yakin akan mengirim data?");']).' ';
                        }

                        if($data->accessSetSetuju()) {
                            $output .= Html::a('<i class="fa fa-check"></i>', ['kegiatan-harian/set-setuju', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Setujui Kegiatan', 'onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']) . ' ';
                        }

                        if($data->accessSetTolak()) {
                            $output .= Html::a('<i class="fa fa-remove"></i>', ['kegiatan-harian/set-tolak', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Tolak Kegiatan', 'onclick' => 'return confirm("Yakin akan menolak kegiatan?");']) . ' ';
                        }

                        $output .= Html::a('<i class="fa fa-eye"></i>',['kegiatan-harian/view','id'=>$data->id,'mode'=>$searchModel->mode],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';

                        if($data->accessUpdate()) {
                            $output .= Html::a('<i class="fa fa-pencil"></i>', ['kegiatan-harian/update', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah']) . ' ';
                        }

                        if($data->accessDelete()) {
                            $output .= Html::a('<i class="fa fa-trash"></i>', ['kegiatan-harian/delete', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Hapus', 'onclick' => 'return confirm("Yakin akan menghapus data?");']) . ' ';
                        }

                        return trim($output);
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:100px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                /*
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['style' => 'text-align:center;width:80px'],
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['kegiatan-harian/pegawai-view', 'id' => $key]);
                        }
                    ]
                ],
                */
            ],
        ]); ?>
    </div>
</div>
