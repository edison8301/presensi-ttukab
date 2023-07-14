<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Instansi;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-index box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Pegawai</h3>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'hover'=>true,
        'striped'=>false,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header'=> 'No',
                'headerOptions' => ['style'=>'text-align:center; width: 60px'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->nama,['/kinerja/pegawai/view','id'=>$data->id]).'<br>NIP. '.$data->nip;
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->instansi ? ($data->instansi->singkatan != null ? $data->instansi->singkatan : $data->instansi->nama) : null;
                },
                'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'id_instansi',
                        'data' => Instansi::getList(),
                        'options' => [
                            'placeholder' => 'Perangkat Daerah',
                        ],
                        'pluginOptions' => ['allowClear' => true],
                    ]),
                'headerOptions' => ['style' => 'text-align:center;width:200px;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'label'=>'Jumlah<br>Kegiatan<br>Tahunan',
                'encodeLabel'=>false,
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->countKegiatanTahunan(['id_kegiatan_tahunan_versi' => 1]).' Kegiatan';
                },
                'headerOptions' => ['style' => 'text-align:center;;vertical-align:middle'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
        ],
    ]); ?>
    </div>
</div>
