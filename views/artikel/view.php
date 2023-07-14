<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Artikel */

$this->title = "Detail Artikel";
$this->params['breadcrumbs'][] = ['label' => 'Artikel', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="artikel-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Detail Artikel</h3>
    </div>

    <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th width="180px" style="text-align:right">{label}</th><td>{value}</td></tr>',
        'attributes' => [
//            [
//                'attribute' => 'id',
//                'format' => 'raw',
//                'value' => $model->id,
//            ],
            [
                'attribute' => 'judul',
                'format' => 'raw',
                'value' => $model->judul,
            ],
            [
                'attribute' => 'slug',
                'format' => 'raw',
                'value' => $model->slug,
            ],
            [
                'attribute' => 'konten',
                'format' => 'raw',
                'value' => $model->konten,
            ],
//            [
//                'attribute' => 'id_user_buat',
//                'format' => 'raw',
//                'value' => $model->id_user_buat,
//            ],
//            [
//                'attribute' => 'id_user_ubah',
//                'format' => 'raw',
//                'value' => $model->id_user_ubah,
//            ],
//            [
//                'attribute' => 'id_artikel_kategori',
//                'format' => 'raw',
//                'value' => $model->id_artikel_kategori,
//            ],
            [
                'attribute' => 'waktu_buat',
                'format' => 'raw',
                'value' => Yii::$app->formatter->asRelativeTime($model->waktu_buat),
            ],
            [
                'attribute' => 'waktu_ubah',
                'format' => 'raw',
                'value' => Yii::$app->formatter->asRelativeTime($model->waktu_ubah),
            ],
            [
                'attribute' => 'waktu_terbit',
                'format' => 'raw',
                'value' => \app\components\Helper::getTanggalDanJam($model->waktu_terbit),
            ],
            [
                'attribute' => 'thumbnail',
                'format' => 'raw',
                'value' => $model->thumbnail,
            ],
        ],
    ]) ?>

    </div>

    <div class="box-footer">
        <?= Html::a('<i class="fa fa-pencil"></i> Sunting Artikel', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']) ?>
        <?= Html::a('<i class="fa fa-list"></i> Daftar Artikel', ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
    </div>

</div>
