<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */

$this->title = "Detail Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// $model->execCmdContentHapus();
?>
<div class="pegawai-view box box-primary">

    <div class="box-header">
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
                'label' => 'Golongan',
                'value' => @$model->pegawaiGolonganBerlaku->golongan->golongan
            ],
            [
                'attribute' => 'id_eselon',
                'value' => $model->getEselonJabatan(),
            ],
            [
                'label' => 'Kelas Jabatan',
                'value' => @$model->getKelasJabatan()
            ],
            [
                'label' => 'Pendidikan',
                'attribute' => 'id_pendidikan',
                'value' => @$model->pendidikan->nama
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-list"></i> Daftar Pegawai', ['index'], ['class' => 'btn btn-primary btn-flat']) ?>
    </div>

</div>

<?= $this->render('_pegawai-instansi',['model' => $model]); ?>

<?php /*
<?= $this->render('_pegawai-golongan',['model' => $model]); ?>
*/ ?>
