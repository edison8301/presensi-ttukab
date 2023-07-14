<?php

use app\components\Helper;
use app\models\Instansi;
use app\models\Jabatan;
use app\modules\tunjangan\models\TingkatanFungsional;
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
                'attribute' => 'id',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center; width: 150px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:left;'],
            ],
            [
                'attribute' => 'id_jenis_jabatan',
                'format' => 'raw',
                'filter' => \app\models\Jabatan::getJenisJabatanList(),
                'headerOptions' => ['style' => 'text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (\app\models\Jabatan $data) {
                    return $data->getJenisJabatan();
                }
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'filter' => Instansi::getList(),
                'headerOptions' => ['style' => 'text-align:center; width: 300px'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'value' => function (Jabatan $data) {
                    return @$data->instansi->nama;
                }
            ],
            [
                'attribute' => 'id_eselon',
                'label' => 'Eselon',
                'format' => 'raw',
                'filter' => \app\models\Eselon::getList(),
                'value' => function (Jabatan $data) {
                    return @$data->eselon->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 300px'],
                'contentOptions' => ['style' => 'text-align:center;'],
            ],
            [
                'attribute' => 'id_tingkatan_fungsional',
                'label' => 'Tingkatan Fungsional',
                'format' => 'raw',
                'filter' => TingkatanFungsional::getList(),
                'value' => function (Jabatan $data) {
                    return @$data->tingkatanFungsional->nama;
                },
                'headerOptions' => ['style' => 'text-align:center; width: 300px'],
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
                'label' => 'Variasi Besaran TPP',
                'format' => 'raw',
                'value' => function (Jabatan $data) {
                    return @$data->getCountBesaranTpp();
                },
                'headerOptions' => ['style' => 'text-align:center; width: 300px'],
                'contentOptions' => ['style' => 'text-align:center;'],

            ],
            [
                'format'=>'raw',
                'value'=>function(Jabatan $data) {
                    return Html::a('<i class="fa fa-eye"></i> Lihat',[
                        '/jabatan/view-tpp',
                        'id' => $data->id
                    ],[
                        'class' => 'btn btn-xs btn-success btn-flat'
                    ]);
                },
                'headerOptions' => ['style'=>'text-align:center;width:80px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
            [
                'format'=>'raw',
                'value'=>function(Jabatan $data) {
                    $output = '';
                    $output .= $data->getLinkIconUpdate().' ';

                    return trim($output);
                },
                'headerOptions' => ['style'=>'text-align:center;width:80px;'],
                'contentOptions'=>['style'=>'text-align:center;']
            ],
        ],
    ]); ?>
    </div>
</div>
