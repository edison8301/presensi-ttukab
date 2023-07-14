<?php

use app\components\Helper;
use app\models\Golongan;
use app\modules\tukin\models\Eselon;
use app\modules\tukin\models\Instansi;
use app\modules\tunjangan\models\JabatanGolongan;
use app\modules\tunjangan\models\JabatanTunjanganGolongan;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tunjangan\models\JabatanTunjanganStrukturalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Jabatan Tunjangan Struktural';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-struktural-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Jabatan Tunjangan Struktural', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Jabatan Tunjangan Struktural', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

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
                'attribute' => 'id_eselon',
                'format' => 'raw',
                'filter' => Eselon::getList(),
                'value' => function($data) {
                    return @$data->eselon->nama;
                    //return @$data->getEditableEselon();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi',
                'filter' => Instansi::getList(),
                'value' => function($data) {
                    return @$data->instansi->nama;
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'kelas_jabatan',
                'label' => 'Kelas<br>Jabatan',
                'encodeLabel' => false,
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            /*
            [
                'attribute' => 'id_golongan',
                'format' => 'raw',
                'filter' => JabatanGolongan::getList(),
                'value' => function($data) {
                    return @$data->getEditableGolongan();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            */
            [
                'attribute' => 'id_jabatan_tunjangan_golongan',
                'format' => 'raw',
                'filter' => JabatanTunjanganGolongan::getList(),
                'value' => function($data) {
                    return @$data->jabatanTunjanganGolongan->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_mulai',
                'label' => 'tanggal mulai<br>berlaku',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::getTanggal(@$data->tanggal_mulai);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'tanggal_selesai',
                'label' => 'tanggal selesai<br>berlaku',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function($data) {
                    return @$data->getLabelTanggalSelesai();
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'besaran_tpp',
                'format' => 'raw',
                'value' => function($data) {
                    return Helper::rp(@$data->besaran_tpp);
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:right;'],
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
