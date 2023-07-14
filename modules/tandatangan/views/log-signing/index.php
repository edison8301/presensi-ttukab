<?php

use app\models\Instansi;
use app\modules\tandatangan\models\Berkas;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tandatangan\models\LogSigningSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Log';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-signing-index box box-primary">

    <div class="box-header">
        <?php /* Html::a('<i class="fa fa-plus"></i> Tambah Log Signing', ['create'], ['class' => 'btn btn-success btn-flat']) */ ?>
        <?php /* Html::a('<i class="fa fa-print"></i> Export Excel Log Signing', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) */ ?>

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
                'headerOptions' => ['style' => 'text-align:center; width: 10px;'],
                'contentOptions' => ['style' => 'text-align:center']
            ],
            [
                'attribute' => 'id_instansi',
                'label' => 'Unit Kerja',
                'format' => 'raw',
                'filter' => Instansi::getList(),
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;width: 280px;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nama_berkas',
                'label' => 'Berkas',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->berkas->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;width: 200px;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Tanggal',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center; width: 200px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
        ],
    ]); ?>
    </div>
</div>
