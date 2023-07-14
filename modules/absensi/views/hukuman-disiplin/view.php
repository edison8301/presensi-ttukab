<?php

use app\components\Helper;
use app\modules\absensi\models\HukumanDisiplinJenis;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\HukumanDisiplin */

$this->title = "Detail Hukuman Disiplin";
$this->params['breadcrumbs'][] = ['label' => 'Hukuman Disiplin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hukuman-disiplin-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Hukuman Disiplin</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'id_pegawai',
                'format' => 'raw',
                'value' => @$model->pegawai->nama,
            ],
            [
                'label' => 'NIP',
                'format' => 'raw',
                'value' => @$model->pegawai->nip,
            ],
            [
                'attribute' => 'id_hukuman_disiplin_jenis',
                'format' => 'raw',
                'value' => $model->hukumanDisiplinJenis->nama,
            ],
            [
                'attribute' => 'bulan',
                'format' => 'raw',
                'value' => $model->getBulanTahun(),
                'visible' => ($model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::RINGAN)
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => Helper::getTanggalSingkat($model->tanggal_mulai),
                'visible' => ($model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::SEDANG
                    OR $model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::BERAT)
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => Helper::getTanggalSingkat($model->tanggal_selesai),
                'visible' => ($model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::SEDANG
                    OR $model->id_hukuman_disiplin_jenis == HukumanDisiplinJenis::BERAT)
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'raw',
                'value' => $model->keterangan,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?php if($model->getAccessUpdate()) { ?>
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Hukuman Disiplin', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?php } ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Hukuman Disiplin', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
