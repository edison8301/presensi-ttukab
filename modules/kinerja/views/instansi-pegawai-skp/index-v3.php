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

if (@$debug == null) {
    $debug = false;
}

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
                    return @$data->instansiPegawai->namaJabatan . ' - ' . @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 300px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label'=>'Tanggal<br/>Berlaku',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function(InstansiPegawaiSkp $data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai).' - <br/>'.
                        Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center; vertical-align: middle; width: 120px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format'=> 'raw',
                'value' => function (InstansiPegawaiSkp $data) use ($debug) {
                    $output = null;
                    $output .= Html::a('<i class="glyphicon glyphicon-eye-open"></i>',[
                        '/kinerja/instansi-pegawai-skp/view-v3',
                        'id' => $data->id
                    ],[
                        'data-toggle' => 'tooltip',
                        'title' => 'Lihat Detail SKP'
                    ]).' ';

                    if ($debug !== false) {
                        $output .= Html::a('<i class="glyphicon glyphicon-trash"></i>',[
                            '/kinerja/instansi-pegawai-skp/delete',
                            'id' => $data->id
                        ],[
                            'data-method' => 'post',
                            'data-confirm' => 'Yakin akan menghapus data?'
                        ]).' ';
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
