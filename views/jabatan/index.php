<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\Jabatan;
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
        <?= $this->render('_modal-instansi'); ?>
        <?php //echo Html::a('<i class="fa fa-print"></i> Export Excel Jabatan', Yii::$app->request->url.'&export=1', ['class' => 'btn btn-success btn-flat']) ?>

    </div>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= /** @noinspection PhpUnhandledExceptionInspection */
    GridView::widget([
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
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => function (Jabatan $data) {
                    return $data->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'filter' => Jabatan::getJenisJabatanList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (Jabatan $data) {
                    $output = $data->getJenisJabatan();
                    if($data->id_jenis_jabatan == 1) {
                        $output .= '<br/>('.@$data->eselon->nama.')';
                    }

                    return $output;
                }
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'filter' => Instansi::getList(),
                'value' => function (Jabatan $data) {
                    return @$data->instansi->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 300px'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'nama_induk',
                'label' => 'Jabatan Atasan',
                'format' => 'raw',
                'value' => function (Jabatan $data) {
                    return @$data->jabatanInduk->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 300px'],
                'contentOptions' => ['style' => 'text-align:left;'],

            ],
            [
                'attribute' => 'status_kepala',
                'label' => 'Kepala',
                'format' => 'raw',
                'value'=>function(Jabatan $data) {
                    return $data->getNamaStatusKepala();
                },
                'filter' => Jabatan::getListStatusKepala(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'nilai_jabatan',
                'label' => 'Nilai<br/>Jabatan',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function (Jabatan $data) {
                    return @$data->nilai_jabatan;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;']
            ],
            [
                'attribute' => 'kelas_jabatan',
                'label' => 'Kelas<br/>Jabatan',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function (Jabatan $data) {
                    return @$data->kelas_jabatan;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 100px'],
                'contentOptions' => ['style' => 'text-align:center;']
            ],
            [
                'label'=>'Mutasi/<br/>Promosi',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function(Jabatan $data) {
                    return Html::a($data->countInstansiPegawai(),[
                       '/instansi-pegawai/index',
                       'InstansiPegawaiSearch[id_jabatan]'=>$data->id
                    ]);
                },
                'headerOptions' => ['style' => 'text-align:center; width: 80px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'format'=>'raw',
                'value'=>function(Jabatan $data) {
                    $output = '';
                    $output .= $data->getLinkIconView().' ';
                    $output .= $data->getLinkIconUpdate().' ';
                    $output .= $data->getLinkIconDelete().' ';

                    return trim($output);
                },
                'headerOptions' => ['style'=>'text-align:center;width:80px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
