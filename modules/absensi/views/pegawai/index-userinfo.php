<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\components\Helper;
use app\models\Instansi;
use app\modules\absensi\models\Absensi;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Absensi Pegawai : '.$searchModel->getMasa();

if($searchModel->mode == 'bawahan')
    $this->title .= ' Bawahan';

$this->params['breadcrumbs'][] = $this->title;


?>

<?= $this->render('_search-index',[
        'searchModel'=>$searchModel,
        'action'=>Url::to(['index-userinfo'])
]); ?>

<div class="pegawai-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-refresh"></i> Refresh Userinfo', ['/absensi/pegawai/set-jumlah-userinfo'], ['class' => 'btn btn-primary btn-flat','onclick'=>'return confirm("Yakin akan merefresh data userinfo?")']) ?>
    </div>

    <div class="box-body table-responsive">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'firstPageLabel' => 'First',
                'lastPageLabel'  => 'Last'
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center; width:60px;vertical-align:middle'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                [
                    'attribute' => 'nama',
                    'format' => 'raw',
                    'value' => function ($data) {

                        return Html::a(Html::encode($data->nama),['/absensi/pegawai/view-userinfo','id'=>$data->id]).'<br>'.$data->nip;
                    },
                    'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'jumlah_userinfo',
                    'label' => 'Jumlah Userinfo',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->jumlah_userinfo;
                    },
                    'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'label' => 'Count Userinfo',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->countUserinfo();
                    },
                    'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                /*
                [
                    'label' => 'Count Checkinout',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->countCheckinout();
                    },
                    'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                */
                [
                    'attribute' => 'id_instansi',
                    'label' => 'Perangkat Daerah',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->getNamaInstansi();
                    },
                    'headerOptions' => ['style' => 'text-align:center;vertical-align:middle; width:250px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'format'=>'raw',
                    'value'=>function($data) {
                        $output  = '';
                        $output .= Html::a('<i class="fa fa-eye"></i>',['view-userinfo','id'=>$data->id],['data-toggle'=>'tooltip','title'=>'Lihat Userinfo']);
                        return trim($output);
                    },
                    'headerOptions'=>['style'=>'text-align:center;width:50px;vertical-align:middle'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ]
            ],
        ]); ?>
    </div>
</div>
