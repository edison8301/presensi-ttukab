<?php

use app\components\Helper;
use app\modules\kinerja\models\KegiatanHarian;
use app\modules\kinerja\models\KegiatanHarianJenis;
use app\modules\kinerja\models\KegiatanStatus;
use app\modules\kinerja\models\Kinerja;
use kartik\grid\GridView;
use yii\helpers\Html; ?>

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
            'visible' => $searchModel->isScenarioPegawai()
        ],
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['style' => 'text-align:center; width: 40px'],
            'contentOptions' => ['style' => 'text-align:center'],
            'checkboxOptions' => function (KegiatanHarian $model, $key, $index, $column) {
                if ($model->id_kegiatan_status==KegiatanStatus::PERIKSA) {
                    return ['value' => $key];
                }
                return ['style' => ['display' => 'none'], 'disabled' => true]; // OR ['disabled' => true]
            },
            'visible' => $searchModel->isScenarioAtasan()
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
                $output = Html::a(Html::encode($data->uraian),['kegiatan-harian/view','id'=>$data->id]).' '.$data->getKeteranganTolak().'<br>';
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
            'value' => function (KegiatanHarian $data) {
                return @$data->getNamaKegiatanHarianJenis();
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
                    $output .= Html::a('<i class="fa fa-send-o"></i>',['/kinerja/kegiatan-harian/set-periksa','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Kirim Untuk Diperiksa','onclick'=>'return confirm("Yakin akan mengirim data?");']).' ';
                }

                if($data->accessSetSetuju()) {
                    $output .= Html::a('<i class="fa fa-check"></i>', ['/kinerja/kegiatan-harian/set-setuju', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Setujui Kegiatan', 'onclick' => 'return confirm("Yakin akan menyetujui kegiatan?");']) . ' ';
                }

                if($data->accessSetKonsep()) {
                    $output .= Html::a('<i class="fa fa-exchange"></i>', ['/kinerja/kegiatan-harian/set-konsep', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah Jadi Konsep', 'onclick' => 'return confirm("Yakin akan mengubah kegiatan jadi konsep?");']) . ' ';
                }

                if($data->accessSetTolak()) {
                    $output .= Html::a('<i class="fa fa-remove"></i>', ['/kinerja/kegiatan-harian/tolak', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Tolak Kegiatan']) . ' ';
                }

                $output .= Html::a('<i class="fa fa-comment"></i>', ['/kinerja/kegiatan-harian/view-catatan', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Lihat Catatan']) . ' ';

                $output .= Html::a('<i class="fa fa-eye"></i>',['/kinerja/kegiatan-harian/view','id'=>$data->id,'mode'=>$searchModel->mode],['data-toggle'=>'tooltip','title'=>'Lihat']).' ';

                if($data->accessUpdate()) {
                    $output .= Html::a('<i class="fa fa-pencil"></i>', ['/kinerja/kegiatan-harian/update-v2', 'id' => $data->id], ['data-toggle' => 'tooltip', 'title' => 'Ubah']) . ' ';
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
