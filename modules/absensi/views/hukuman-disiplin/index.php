<?php

use app\models\Instansi;
use app\modules\absensi\models\HukumanDisiplin;
use app\modules\absensi\models\HukumanDisiplinJenis;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\absensi\models\HukumanDisiplinSearch */

$this->title = 'Daftar Hukuman Disiplin '.@$searchModel->hukumanDisiplinJenis->nama;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hukuman-disiplin-index box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title; ?></h3>
    </div>

    <div class="box-body">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <div style="margin-bottom: 20px">
            <?php if(HukumanDisiplin::accessCreate(['id_hukuman_disiplin_jenis'=>HukumanDisiplinJenis::RINGAN])) { ?>
            <?= Html::a('<i class="fa fa-plus"></i> Hukuman Disiplin Ringan', [
                '/absensi/hukuman-disiplin/create',
                'id_hukuman_disiplin_jenis'=>HukumanDisiplinJenis::RINGAN
            ], ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>

            <?php if(HukumanDisiplin::accessCreate(['id_hukuman_disiplin_jenis'=>HukumanDisiplinJenis::SEDANG])) { ?>
                <?= Html::a('<i class="fa fa-plus"></i> Hukuman Disiplin Sedang', [
                    '/absensi/hukuman-disiplin/create',
                    'id_hukuman_disiplin_jenis'=>HukumanDisiplinJenis::SEDANG
                ], ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>

            <?php if(HukumanDisiplin::accessCreate(['id_hukuman_disiplin_jenis'=>HukumanDisiplinJenis::BERAT])) { ?>
                <?= Html::a('<i class="fa fa-plus"></i> Hukuman Disiplin Berat', [
                    '/absensi/hukuman-disiplin/create',
                    'id_hukuman_disiplin_jenis'=>HukumanDisiplinJenis::BERAT
                ], ['class' => 'btn btn-success btn-flat']) ?>
            <?php } ?>
        </div>

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
                    'attribute' => 'id_pegawai',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                    'value' => function(HukumanDisiplin $data) {
                        return @$data->pegawai->nama.'<br/>'.
                            'NIP.'.@$data->pegawai->nip;
                    }
                ],
                [
                    'attribute' => 'id_hukuman_disiplin_jenis',
                    'label' => 'Jenis',
                    'format' => 'raw',
                    'filter' => HukumanDisiplinJenis::getList(),
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                    'value' => function(HukumanDisiplin $data) {
                        return $data->hukumanDisiplinJenis->nama;
                    }
                ],
                [
                    'attribute' => 'tanggal_mulai',
                    'label' => 'Periode',
                    'value' => function(HukumanDisiplin $data) {
                        return $data->getPeriode();
                    },
                    'headerOptions' => ['style' => 'text-align:center; width: 120px'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],
                [
                    'attribute' => 'id_instansi',
                    'header' => 'Perangkat Daerah',
                    'format' => 'raw',
                    'filter' => Instansi::getList(),
                    'value' => function (HukumanDisiplin $data) {
                        return @$data->instansi->nama;
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left; width:200px'],
                ],
                [
                    'attribute' => 'keterangan',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:left;'],
                ],
                [
                    'format' => 'raw',
                    'value' => function(HukumanDisiplin $data) {
                        $output = '';
                        $output .= $data->getLinkViewIcon().' ';
                        $output .= $data->getLinkUpdateIcon().' ';
                        $output .= $data->getLinkDeleteIcon();

                        return trim($output);
                    },
                    'headerOptions' => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ]
                /*
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['style' => 'text-align:center;width:80px']
                ],
                */
            ],
        ]); ?>
    </div>
</div>
