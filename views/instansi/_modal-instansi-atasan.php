<?php

use app\models\Instansi;
use app\models\InstansiSearch;
use app\models\Pegawai;
use app\models\PegawaiSearch;
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
/* @var $id_instansi int|null */

$searchModel = new InstansiSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$dataProvider->pagination = ['pageSize'=>5];
?>

<?php Modal::begin([
    'header' => '<h4>Pilih Instansi</h4>',
    'id' => "modal-instansi",
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => [
        'tag'=>'a',
        'label'=>'<i class="fa fa-edit"></i> Ubah Atasan Kepala',
        'class'=>'btn btn-primary btn-flat'
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
            'headerOptions' => ['style' => 'text-align:center;width:50px;'],
            'contentOptions' => ['style' => 'text-align:center']
        ],
        [
            'attribute' => 'nama',
            'format' => 'raw',
            'value' => function (Instansi $data) use ($id_instansi) {
                return @Html::a($data->nama,[
                    '/jabatan/update-atasan-kepala-v2',
                    'id_instansi' => $id_instansi,
                    'id_instansi_atasan' => $data->id,
                ]);
            },
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:left;'],
        ],
        [
            'format' => 'raw',
            'value'=>function(Instansi $data) use ($id_instansi) {
                return Html::a('<i class="fa fa-check"></i> Pilih',[
                    '/jabatan/update-atasan-kepala-v2',
                    'id_instansi' => $id_instansi,
                    'id_instansi_atasan' => $data->id,
                ],['class'=>'btn btn-xs btn-success btn-flat']);
            },
            'headerOptions' => ['style' => 'text-align:center;width:80px;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
<?php Modal::end(); ?>
