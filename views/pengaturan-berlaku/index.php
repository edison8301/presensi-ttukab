<?php

use app\components\Helper;
use app\models\Pengaturan;
use app\models\PengaturanBerlaku;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PengaturanBerlakuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pengaturan Berlaku';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-berlaku-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pengaturan', [
            '/pengaturan/index',
        ], ['class' => 'btn btn-primary btn-flat']) ?>

        <?= Html::a('<i class="fa fa-plus"></i> Tambah Pengaturan Berlaku', [
            '/pengaturan-berlaku/create',
            'id_pengaturan' => $searchModel->id_pengaturan
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
                'headerOptions' => ['style' => 'text-align:center;width:10px;'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_pengaturan',
                'format' => 'raw',
                'visible' => $searchModel->id_pengaturan == null,
                'value' => function(PengaturanBerlaku $data) {
                    return @$data->pengaturan->nama;
                },
                'filter' => Pengaturan::findArrayDropdown(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nilai',
                'format' => 'raw',
                'value' => function(PengaturanBerlaku $data) {
                    return @$data->getNamaNilai();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function(PengaturanBerlaku $data) {
                    return Helper::getTanggalSingkat($data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function(PengaturanBerlaku $data) {
                    return Helper::getTanggalSingkat($data->tanggal_selesai);
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
