<?php

use app\components\Helper;
use app\models\User;
use app\modules\kinerja\models\InstansiPegawaiSkp;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\InstansiPegawaiSkpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $debug bool */

$this->title = 'Daftar SKP Pegawai Tahun '.User::getTahun();
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('//filter/_filter-tahun'); ?>

<div class="instansi-pegawai-skp-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Perbarui Daftar SKP', [
            '/kinerja/instansi-pegawai-skp/refresh',
            'id'=>$searchModel->id_pegawai
        ], ['class' => 'btn btn-success btn-flat']) ?>
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
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 50px'],
                'contentOptions' => ['style' => 'text-align:center;']
            ],
            [
                'attribute' => 'nama_pegawai',
                'format' => 'raw',
                'value'=>function($data) {
                    $output = @$data->pegawai->nama.'<br/>';
                    $output .= 'NIP.'.@$data->pegawai->nip;
                    return  $output;
                },
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nomor',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle;width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute'=>'nama_jabatan',
                'format'=>'raw',
                'value'=>function(InstansiPegawaiSkp $data) {
                    return @$data->instansiPegawai->namaJabatan.' - '.
                        @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 300px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label'=>'Tanggal<br/>Berlaku',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai).' - <br/>'.
                        Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 120px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br/>Kinerja<br/>Utama',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function(InstansiPegawaiSkp $data) {
                    return Html::a($data->countKegiatanTahunanV2([
                            'id_kegiatan_tahunan_jenis' => 1,
                            'id_kegiatan_tahunan_versi' => 2,
                        ]),[
                        '/kinerja/kegiatan-tahunan/index-v2',
                        'KegiatanTahunanSearch[nomor_skp]'=>$data->nomor,
                        'KegiatanTahunanSearch[id_pegawai]'=>$data->instansiPegawai->id_pegawai,
                    ],[
                        'data-toggle'=>'tooltip',
                        'title'=>'Lihat Daftar Kinerja'
                    ]);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi_pegawai',
                'label' => 'ID Instansi<br/>Pegawai',
                'encodeLabel' => false,
                'value'=>function($data) {
                    return $data->id_instansi_pegawai;
                },
                'visible'=>(@$debug==true),
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                 'format'=> 'raw',
                 'value' => function(InstansiPegawaiSkp $data) {
                        $output = '';
                        if($data->accessView()) {
                            $output .= Html::a('<i class="glyphicon glyphicon-th-list"></i>',[
                                    '/kinerja/kegiatan-tahunan/index-v2',
                                    'KegiatanTahunanSearch[id_pegawai]'=>@$data->instansiPegawai->id_pegawai,
                                    'KegiatanTahunanSearch[id_instansi_pegawai]'=>$data->id_instansi_pegawai,
                                    'KegiatanTahunanSearch[nomor_skp]'=>$data->nomor
                                ],[
                                    'data-toggle'=>'tooltip',
                                    'title'=>'Lihat Daftar Kinerja'
                                ]).' ';

                            $output .= Html::a('<i class="glyphicon glyphicon-th-large"></i>',[
                                '/kinerja/kegiatan-tahunan/matriks-v2',
                                'KegiatanTahunanSearch[id_pegawai]'=>$data->instansiPegawai->id_pegawai,
                                'KegiatanTahunanSearch[nomor_skp]'=>$data->nomor
                            ],[
                                'data-toggle'=>'tooltip',
                                'title'=>'Lihat Matriks Kinerja'
                            ]).' ';

                            $output .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>',[
                               '/kinerja/instansi-pegawai-skp/view-v2',
                               'id'=>$data->id
                            ],[
                                'data-toggle'=>'tooltip',
                                'title'=>'Lihat Tampilan SKP'
                            ]).' ';
                        }

                     if($data->accessRefresh()) {
                         $output .= Html::a('<i class="glyphicon glyphicon-refresh"></i>', [
                                 '/kinerja/instansi-pegawai-skp/refresh',
                                 'id' => $data->id
                             ], [
                                 'data-toggle' => 'tooltip',
                                 'title' => 'Refresh SKP'
                             ]) . ' ';
                     }

                        if($data->accessDelete()) {
                            $output .= Html::a('<i class="glyphicon glyphicon-trash"></i>', [
                                    '/kinerja/instansi-pegawai-skp/delete',
                                    'id' => $data->id
                                ], [
                                    'data-method'=>'post',
                                    'data-confirm'=>'Yakin akan menghapus data?',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Hapus Data'
                                ]) . ' ';
                        }
                        return trim($output);
                 },
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ]
        ],
    ]); ?>
    </div>
</div>
