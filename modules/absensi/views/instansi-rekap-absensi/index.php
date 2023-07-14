<?php

use app\components\Session;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $instansiRekapAbsensiSearch \app\modules\absensi\models\InstansiRekapAbsensiSearch */

$this->title = 'Daftar Instansi Rekap Absensi';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun') ?>

<?= $this->render('_search-index',[
        'instansiRekapAbsensiSearch'=>$instansiRekapAbsensiSearch,
        'action'=>Url::to(['index'])
]); ?>

<div class="instansi-rekap-absensi-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Instansi Rekap Absensi', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-wrench"></i> Setup Rekap Absensi', ['setup','bulan'=>$instansiRekapAbsensiSearch->bulan], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Rekap Absensi', [
            'refresh-all','bulan'=>$instansiRekapAbsensiSearch->bulan,
        ], [
            'class' => 'btn btn-success btn-flat',
            'data-confirm' => 'Yakin akan me-refresh rekap absensi?'
        ]) ?>
    </div>

    <div class="box-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $instansiRekapAbsensiSearch,
            'options' => [
                'firstPageLabel' => 'Awal',
                'lastPageLabel' => 'Akhir',
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                [
                    'attribute' => 'nama_instansi',
                    'format' => 'raw',
                    'value'=>function($data) use ($instansiRekapAbsensiSearch) {
                        if(Session::isAdmin()) {
                            return Html::a(@$data->instansi->nama,[
                                '/instansi/view-pegawai-rekap-absensi',
                                'id' => $data->id_instansi,
                                'bulan' => $instansiRekapAbsensiSearch->bulan,
                                'tahun' => Session::getTahun(),
                            ]);
                        }
                        return @$data->instansi->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'bulan',
                    'format' => 'raw',
                    'value'=>function($data) {
                        return Helper::getBulanSingkat($data->bulan).'<br>'.$data->tahun;
                    },
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_hadir',
                    'label'=>'Hdr<br/>(%)',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_tidak_hadir',
                    'label'=>'Tdk Hdr<br/>(%)',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_tanpa_keterangan',
                    'label'=>'TK<br/>(%)',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                /*
                [
                    'attribute' => 'persen_potongan_fingerprint',
                    'label'=>'Pot<br>FP (%)',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_potongan_kegiatan',
                    'label'=>'Pot<br>Keg (%)',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'persen_potongan_total',
                    'label'=>'Pot<br>Ttl (%)',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;width:60px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                */
                [
                    'attribute' => 'waktu_diperbarui',
                    'label'=>'Waktu<br>Diperbarui',
                    'encodeLabel'=>false,
                    'format' => 'raw',
                    'value' => function($data) {
                        return $data->waktu_diperbarui;
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 50px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'format'=>'raw',
                    'value'=>function($data) use ($instansiRekapAbsensiSearch) {
                        $output = Html::a('<i class="fa fa-refresh"></i>',['refresh','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Refresh Nilai']);
                        return $output;
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 50px'],
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
