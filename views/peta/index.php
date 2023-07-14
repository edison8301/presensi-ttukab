<?php

use app\components\Session;
use app\models\Peta;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PetaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Peta';
$this->params['breadcrumbs'][] = $this->title;

$mode = $searchModel->mode;

?>
<div class="peta-index box box-primary">

    <div class="box-header">
        <?php if (Peta::accessCreate()) { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Peta', [
                'create', 'mode' => $mode
            ], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Peta', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>
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
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a($data->nama,['peta/view','id' => $data->id]);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nama_instansi',
                'label' => 'Instansi',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'visible' => $mode == 'instansi' AND Session::isAdmin(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nama_pegawai',
                'label' => 'Pegawai',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->pegawai->nama;
                },
                'visible' => $mode == 'pegawai' OR $mode == 'pegawai-wfh',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nip_pegawai',
                'label' => 'NIP',
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->pegawai->nip;
                },
                'visible' => $mode == 'pegawai' OR $mode == 'pegawai-wfh',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'latitude',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;width:50px'],
            ],
            [
                'attribute' => 'longitude',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;width:50px'],
            ],
            [
                'attribute' => 'jarak',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;width:50px'],
            ],
            [
                'attribute' => 'status_kunci',
                'label' => 'Status<br>Kunci',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function (Peta $data) {
                    return $data->getSetStatusKunci();
                },
                'visible' => $mode == 'pegawai-wfh' OR $mode == 'khusus',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;width:50px'],
            ],
            [
                'format' => 'raw',
                'value' => function(Peta $data) use($mode) {
                    $btn = $data->getLinkIconShow($mode);
                    $btn .= ' '.$data->getLinkIconEdit($mode);
                    $btn .= ' '.$data->getLinkIconDelete();

                    return $btn;
                },
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
