<?php

use app\models\Instansi;
use app\modules\kinerja\models\InstansiPegawaiSkpSearch;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this \yii\web\View */
/* @var $instansi Instansi */
/* @var $searchModel \app\modules\kinerja\models\KegiatanRhkSearch */

$instansiPegawaiSkpSearch = new InstansiPegawaiSkpSearch([
    'tahun' => \app\components\Session::getTahun(),
    'id_pegawai' => $searchModel->id_pegawai,
]);

$dataProvider = $instansiPegawaiSkpSearch->search(Yii::$app->request->queryParams);
$dataProvider->pagination = ['pageSize'=>5];

?>

<?php Modal::begin([
    'header' => '<h4 class="modal-title">Pilih SKP Pegawai</h4>',
    'id' => "modal-instansi",
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => [
        'tag'=>'a',
        'label'=>'<i class="fa fa-plus"></i> Tambah RHK',
        'class'=>'btn btn-success btn-flat'
    ],
]); ?>

<div style="margin-bottom: 20px">
    <?= Html::a('<i class="fa fa-refresh"></i> Perbarui Daftar SKP', [
        '/kinerja/instansi-pegawai-skp/refresh',
        'id' => $searchModel->id_pegawai
    ], ['class' => 'btn btn-success btn-flat']) ?>
</div>

<?php Pjax::begin(['timeout' => 10000]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'options' => [
        'id' => 'refmodal',
    ],
    'pager' => [
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last',
    ],
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'No',
            'headerOptions' => ['style' => 'text-align:center; width: 60px'],
            'contentOptions' => ['style' => 'text-align:center']
        ],
        [
            'label' => 'jabatan',
            'format' => 'raw',
            'filter' => '',
            'value' => function($data) {
                return @$data->instansiPegawai->namaJabatan.' - '.
                    @$data->instansi->nama;
            },
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:left;'],
        ],
        [
            'attribute' => 'nomor',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'format' => 'raw',
            'value'=>function($data) {
                return Html::a('<i class="fa fa-check"></i> Pilih',[
                    '/kinerja/kegiatan-rhk/create',
                    'id_instansi_pegawai_skp' => $data->id

                ],['class'=>'btn btn-xs btn-success btn-flat']);
            },
            'headerOptions' => ['style' => 'text-align:center; width: 80px'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
<?php Modal::end(); ?>
