<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;
use \app\modules\kinerja\models\PegawaiRekapKinerja;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\kinerja\models\PegawaiRekapKinerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai Rekap Kinerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_search-index',[
    'searchModel'=>$searchModel,
    'action'=>Url::to(['index'])
]); ?>
<div class="pegawai-rekap-kinerja-index box box-primary">

    <div class="box-header">
        <?= Html::a(
            '<i class="fa fa-refresh"></i> Refresh Rekap Kinerja',
            [
                'pegawai-rekap-kinerja/refresh',
                'id_instansi' => $searchModel->id_instansi,
                'bulan' => $searchModel->bulan
            ],
            [
                'class' => 'btn btn-success btn-flat',
                'data-confirm' => 'Lakukan refresh data?',
            ]
        ) ?>

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
                'headerOptions' => ['style' => 'text-align:center'],
                'contentOptions' => ['style' => 'text-align:center']
            ],

            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
                'value' => function (PegawaiRekapKinerja $data) {
                    return Html::a(@$data->pegawai->nama, ['pegawai-rekap-kinerja/view', 'id' => $data->id])
                        . '<br>'
                        . @$data->pegawai->nip;
                }
            ],
            [
                'attribute' => 'bulan',
                'filter' => Helper::getListBulan(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (PegawaiRekapKinerja $data) {
                    return Helper::getBulanLengkap($data->bulan);
                }
            ],
            [
                'attribute' => 'potongan_skp',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'potongan_ckhp',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'progres',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function ($data) {
                    return $data->progres;
                }
            ],
            [
                'attribute' => 'potongan_total',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],

        ],
    ]); ?>
    </div>
</div>
