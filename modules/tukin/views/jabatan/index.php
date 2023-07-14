<?php

use app\components\Helper;
use app\modules\tukin\models\Jabatan;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tukin\models\JabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Jabatan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Jabatan', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-print"></i> Export Excel Jabatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

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
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'filter' => \app\modules\tukin\models\Jabatan::getJenisJabatanList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Jabatan $data) {
                    return $data->getJenisJabatan();
                }
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'filter' => \app\modules\tukin\models\Instansi::getList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\modules\tukin\models\Jabatan $data) {
                    return @$data->instansi->nama;
                }
            ],
            [
                'attribute' => 'kelas_jabatan',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'jumlah_tetap',
                'format' => 'raw',
                'filter' => [0 => 'Tidak Aktif', 1 => 'Aktif'],
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (Jabatan $row) {
                    return $row->getIsJumlahTetap() ? Helper::rp($row->jumlah_tetap) : 'Tidak Aktif';
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'text-align:center;width:80px']
            ],
        ],
    ]); ?>
    </div>
</div>
