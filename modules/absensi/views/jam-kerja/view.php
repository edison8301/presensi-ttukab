<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerja */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_shift_kerja',
            'hari',
            'jenis',
            'nama',
            'jam_mulai_pindai',
            'jam_selesai_pindai',
            'jam_mulai_normal',
            'jam_selesai_normal',
            'status_wajib',
        ],
    ]) ?>

</div>
