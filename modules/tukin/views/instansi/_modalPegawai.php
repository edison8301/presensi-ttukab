<?php

use app\modules\tukin\models\Instansi;
use app\modules\tukin\models\PegawaiSearch;
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

$searchModel = new PegawaiSearch();
$searchModel->searchInstansi = $instansi;
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
?>
<?php Modal::begin([
    'header' => '<h2>Pilih Pegawai</h2>',
    'id' => "modal-pegawai",
    'size' => Modal::SIZE_LARGE,
    'toggleButton' => false,
]); ?>


<?php Pjax::begin(['timeout' => 10000]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => [
        'id' => 'refmodal',
    ],
    'columns' => [
        [
            'attribute' => 'nama',
            'format' => 'raw',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'value' => function (\app\modules\tukin\models\Pegawai $data) {
                return "$data->nama <br> $data->nip";
            }
        ],
        [
            'attribute' => 'id_instansi',
            'format' => 'raw',
            'filter' => $instansi->getListInstansiFilter(),
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'value' => function (\app\modules\tukin\models\Pegawai $data) {
                return @$data->instansi->nama;
            }
        ],
        'nama_jabatan',
        [
            'attribute' => 'id_eselon',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'value' => function (\app\modules\tukin\models\Pegawai $data) {
                return @$data->eselon->nama;
            }
        ],

        [
            'class' => 'app\components\ToggleActionColumn',
            'template' => '{create}',
            'buttons' => [
                'create' => function ($url, $row, $key){
                    return Html::a(
                        '<i class="fa fa-plus"></i>',
                        '#',
                        [
                            'onclick' => "id_pegawai = $row->id;redirect();",
                            'title' => 'Tambah Sebagai Pemangku Jabatan',
                            'data-toggle' => 'tooltip',
                        ]
                    );
                }
            ],
            'headerOptions'=>['style'=>'text-align:center;width:80px'],
            'contentOptions'=>['style'=>'text-align:center']
        ],
    ],
]); ?>
<?php Pjax::end(); ?>
<?php Modal::end(); ?>
