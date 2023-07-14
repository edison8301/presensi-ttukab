<?php

use app\components\Helper;
use app\models\InstansiPegawai;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Bulan '.$searchModel->getBulanLengkapTahun();
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun') ?>

<?= $this->render('_filter',[
    'searchModel'=>$searchModel,
    'action'=>Url::to(['/tukin/instansi-pegawai/index-pegawai'])
]); ?>

<div class="instansi-pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-print"></i> Export PDF Rekap Absensi', Yii::$app->request->url.'&export-pdf=1', ['class' => 'btn btn-primary btn-flat','target'=>'_blank']) ?>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
        ],
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
                'value'=>function($data) use ($searchModel) {
                    return Html::a(@$data->pegawai->nama,[
                       '/tukin/pegawai/view','id'=>$data->id_pegawai,
                       'PegawaiSearch[bulan]'=>$searchModel->bulan
                    ]).'<br/>NIP.'.@$data->pegawai->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 250px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'nama_jabatan',
                'format' => 'raw',
                'value' => function(InstansiPegawai $data) {
                    return @$data->getNamaJabatan();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_berlaku',
                'label'=>'Tanggal<br/>TMT',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_berlaku);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label'=>'Tanggal<br/>Mulai<br/>Efektif',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'label'=>'Tanggal<br/>Selesai<br/>Efektif',
                'encodeLabel' => false,
                'format' => 'raw',
                'value'=>function($data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format' => 'raw',
                'value'=>function(InstansiPegawai $data) use ($searchModel) {
                    $output = Html::a('<i class="glyphicon glyphicon-eye-open"></i>',[
                            '/tukin/pegawai/view','id'=>$data->id_pegawai,
                            'PegawaiSearch[bulan]'=>$searchModel->bulan
                        ]).' ';

                    $output .= $data->getLinkIconUpdate().' ';

                    return trim($output);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 50px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
        ],
    ]); ?>
    </div>
</div>
