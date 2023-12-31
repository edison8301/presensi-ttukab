<?php

use app\models\Instansi;
use app\models\InstansiSearch;
use app\models\PegawaiSearch;
use app\models\Pegawai;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 11/16/2018
 * Time: 2:12 PM
 */

/* @var $this \yii\web\View */
/* @var $instansi Instansi */

$searchModel = new InstansiSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$dataProvider->pagination = ['pageSize'=>5];
?>

<?php Modal::begin([
    'header' => '<h4 class="modal-title">Pilih Perangkat Daerah</h4>',
    'id' => "modal-pegawai",
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => [
        'tag'=>'a',
        'label'=>'<i class="fa fa-plus"></i> Tambah Data',
        'class'=>'btn btn-success btn-flat'
    ],
]); ?>


<?php Pjax::begin(['timeout' => 10000]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
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
            'attribute' => 'nama',
            'format' => 'raw',
            'value' => function (Instansi $data) {
                return @Html::a($data->nama,[
                    '/absensi/instansi-absensi-manual/create',
                    'id_instansi'=>$data->id,
                ]);
            },
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:left;'],
        ],
        [
            'attribute' => 'id_instansi_jenis',
            'format' => 'raw',
            'value' => function (Instansi $data) {
                return $data->instansiJenis->nama;
            },
            'filter' => \app\models\InstansiJenis::getList(),
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'format' => 'raw',
            'value'=>function($data) {
                return Html::a('<i class="fa fa-check"></i> Pilih',[
                    '/absensi/instansi-absensi-manual/create',
                    'id_instansi'=>$data->id,
                ],['class'=>'btn btn-xs btn-success btn-flat']);
            },
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
<?php Modal::end(); ?>
