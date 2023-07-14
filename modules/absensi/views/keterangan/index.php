<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\absensi\models\KeteranganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Keterangan/Izin';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keterangan-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Keterangan/Izin',['/absensi/keterangan/create'],['class'=>'btn btn-flat btn-success']); ?>
    </div>
    <div class="box-body">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'nip',
                    'label'=>'NIP',
                    'value'=>function($data) {
                        return $data->nip;
                    },
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute'=>'tanggal',
                    'label'=>'Tanggal',
                    'value'=>function($data) {
                        return Helper::getTanggalSingkat($data->tanggal);
                    },
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'attribute'=>'id_keterangan_jenis',
                    'value'=>function($data) {
                        return $data->getRelationField("keteranganJenis","nama");
                    },
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions'=>['style'=>'text-align:center;'],
                    'contentOptions'=>['style'=>'text-align:center;'],
                ],
            ],
        ]); ?>

    </div>
</div>
