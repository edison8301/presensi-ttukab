<?php

use app\components\Helper;
use app\models\InstansiPegawai;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InstansiPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Pegawai';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_filter',[
    'searchModel'=>$searchModel,
    'action' => Url::to(['/kinerja/instansi-pegawai/index-rekap-kegiatan-harian-v3'])
]); ?>

<div class="instansi-pegawai-index box box-primary">

    <div class="box-body">

        <div style="margin-bottom: 20px;">
            <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel', Yii::$app->request->url.'&export-excel=1', [
                'class' => 'btn btn-success btn-flat',
            ]) ?>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pager' => [
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last',
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No',
                    'headerOptions' => ['style' => 'text-align:center'],
                    'contentOptions' => ['style' => 'text-align:center']
                ],
                [
                    'attribute' => 'nama_pegawai',
                    'format' => 'raw',
                    'value'=>function($data) use ($searchModel) {
                        return Html::a(@$data->pegawai->nama,[
                                '/kinerja/pegawai/view','id'=>$data->id_pegawai,
                                'PegawaiSearch[bulan]'=>$searchModel->bulan
                            ]).'<br/>NIP.'.@$data->pegawai->nip;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'id_instansi',
                    'format' => 'raw',
                    'value' => function($data) {
                        return @$data->instansi->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 360px;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'attribute' => 'nama_jabatan',
                    'format' => 'raw',
                    'value' => function(InstansiPegawai $data) {
                        return @$data->getNamaJabatan();
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 360px;'],
                    'contentOptions' => ['style' => 'text-align:left'],
                ],
                [
                    'format' => 'raw',
                    'value'=>function($data) use ($searchModel) {
                        $bulan = $searchModel->bulan;
                        if (strlen($bulan) == 1) {
                            $bulan = "0$bulan";
                        }

                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>',[
                            '/kinerja/pegawai/view-rekap-kegiatan-harian-v3','id'=>$data->id_pegawai,
                            'RekapHarianForm[bulan]' => $bulan,
                        ]);
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 50px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
            ],
        ]); ?>
    </div>
</div>
