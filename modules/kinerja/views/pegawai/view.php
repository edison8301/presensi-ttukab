<?php

use app\components\Helper;
use app\widgets\LabelKegiatan;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="pegawai-view box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Detail Pegawai</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'nama',
                'format' => 'raw',
                'value' => $model->nama,
            ],
            [
                'attribute' => 'nip',
                'format' => 'raw',
                'value' => $model->nip,
            ],
            [
                'attribute' => 'id_instansi',
                'format' => 'raw',
                'value' => @$model->instansiPegawaiBerlaku->getNamaInstansi(),
            ],
            [
                'attribute' => 'id_jabatan',
                'format' => 'raw',
                'value' => @$model->getNamaJabatan(),
            ],
            [
                'label' => 'Kelas Jabatan',
                'value' => @$model->getKelasJabatan()
            ],
            [
                'attribute' => 'id_atasan',
                'format' => 'raw',
                'value' => @$model->instansiPegawaiBerlaku->atasan->nama,
            ],
        ],
    ]) ?>
    </div>
</div>

<?= $this->render('_kegiatan-bulanan',[
    'model' => $model,
    'searchModel' => $searchModel,
]); ?>
