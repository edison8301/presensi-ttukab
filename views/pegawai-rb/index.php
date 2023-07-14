<?php

use app\components\Helper;
use app\components\Session;
use app\models\PegawaiRb;
use app\models\PegawaiRbJenis;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PegawaiRbSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai ' . @$searchModel->pegawaiRbJenis->nama . ' Tahun ' . Session::getTahun();
$this->params['breadcrumbs'][] = $this->title;

$jenis = 'Simadig';
if ($searchModel->id_pegawai_rb_jenis != PegawaiRbJenis::PEMUTAKHIRAN_SIMADIG) {
    $jenis = 'Bangkom';
}

?>

<?= $this->render('_index-search', [
    'searchModel' => $searchModel,
    'action' => ['/pegawai-rb/index', 'id_pegawai_rb_jenis' => $searchModel->id_pegawai_rb_jenis],
]) ?>

<div class="pegawai-rb-index box box-primary">

    <div class="box-header">
        <?= Html::a('<i class="fa fa-plus"></i> Tambah Data', [
            'create',
            'id_pegawai_rb_jenis' => $searchModel->id_pegawai_rb_jenis,
        ], ['class' => 'btn btn-success btn-flat']) ?>

        <?php if ($searchModel->id_pegawai_rb_jenis != null) { ?>
            <?= Html::a("<i class='fa fa-refresh'></i> Refresh From $jenis", [
                '/pegawai-rb/refresh',
                'id_pegawai_rb_jenis' => $searchModel->id_pegawai_rb_jenis,
                'id_instansi' => $searchModel->id_instansi,
            ], [
                'class' => 'btn btn-primary btn-flat',
                'data-confirm' => 'Yakin akan merefresh realisasi?',
            ]) ?>
        <?php } ?>
    </div>

    <div class="box-body">

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
                'attribute' => 'nama_pegawai',
                'format' => 'raw',
                'value' => function (PegawaiRb $data) {
                    return @$data->pegawai->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'raw',
                'value' => function (PegawaiRb $data) {
                    return Editable::widget([
                        'model'=> $data,
                        'name' => 'tanggal',
                        'value'=> $data->tanggal,
                        'displayValue' => Helper::getTanggal($data->tanggal),
                        'valueIfNull' => '<i>(belum diset)</i>',
                        'beforeInput' => Html::hiddenInput('editableKey', $data->id),
                        'placement' => 'top',
                        'formOptions' => ['action' => ['/pegawai-rb/editable-update']],
                        'inputType' => Editable::INPUT_DATE,
                        'options'=>[
                            'removeButton' => false,
                            'pluginOptions'=> ['format'=>'yyyy-mm-dd'] // javascript format
                        ],
                    ]);
                },
                'headerOptions' => ['style' => 'text-align:center;width:150px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_pegawai_rb_jenis',
                'format' => 'raw',
                'label' => 'Jenis',
                'visible' => $searchModel->id_pegawai_rb_jenis == null,
                'value' => function (PegawaiRb $data) {
                    return @$data->pegawaiRbJenis->nama;
                },
                'headerOptions' => ['style' => 'text-align:center;width:250px;'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'status_realisasi',
                'format' => 'raw',
                'label' => 'Status',
                'value' => function (PegawaiRb $data) {
                    return $data->getLabelStatusRealisasi();
                },
                'headerOptions' => ['style' => 'text-align:center;width:100px;'],
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
