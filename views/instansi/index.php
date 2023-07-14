<?php

use app\models\Instansi;
use app\models\InstansiJenis;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Unit Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_filter',['searchModel'=>$searchModel]); ?>

<div class="instansi-index box box-primary">

    <div class="box-header">
        <?= Instansi::getLinkButtonCreate() ?>
    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizableColumns'=>true,
        'floatHeader'=>false,
        'floatHeaderOptions'=>['scrollingTop'=>'0'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header'=> 'No',
                'headerOptions' => ['style'=>'text-align:center'],
                'contentOptions' => ['style'=>'text-align:center']
            ],
            [
                'attribute'=>'nama',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:left;'],
            ],
            [
                'attribute'=>'singkatan',
                'format'=>'raw',
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'attribute'=>'id_instansi_jenis',
                'format'=>'raw',
                'value' => function(Instansi $data) {
                    return $data->instansiJenis->nama;
                },
                'filter'=>InstansiJenis::getList(),
                'headerOptions'=>['style'=>'text-align:center;'],
                'contentOptions'=>['style'=>'text-align:center;'],
            ],
            [
                'label'=>'Username',
                'format'=>'raw',
                'value'=>function(Instansi $data) {
                    return $data->getUsername();
                },
                'headerOptions' => ['style'=>'text-align:center;width:150px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
            [
                'attribute' => 'status_aktif',
                'label' => 'Status',
                'format'=>'raw',
                'filter' => [1 => 'Aktif', 0 => 'Tidak Aktif'],
                'value'=>function(Instansi $data) {
                    return $data->getLabelStatusAktif();
                },
                'headerOptions' => ['style'=>'text-align:center;width:80px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
            [
                'format'=>'raw',
                'value'=>function(Instansi $data) {
                    $output = '';
                    $output .= $data->getLinkIconViewJabatan().' ';
                    $output .= $data->getLinkIconUserSetPassword().' ';
                    $output .= $data->getLinkIconView().' ';
                    $output .= $data->getLinkIconUpdate().' ';
                    $output .= $data->getLinkIconDelete().' ';

                    return trim($output);
                },
                'headerOptions' => ['style'=>'text-align:center;width:100px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
