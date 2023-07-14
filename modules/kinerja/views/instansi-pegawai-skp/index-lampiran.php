<?php

/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\kinerja\models\InstansiPegawaiSkpSearch */
/* @var $model \app\modules\kinerja\models\InstansiPegawaiSkp */

$this->title = 'Lampiran SKP';
$this->params['breadcrumbs'][] = ['label' => 'Kinerja Tahunan', 'url' => ['kegiatan-rhk/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_filter-index-lampiran', [
    'searchModel' => $searchModel,
]) ?>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Lampiran SKP</h3>
    </div>

    <div class="box-body">

        <?php if ($model == null) { ?>
            <i>Silahkan pilih skp terlebibh dahulu</i>
        <?php } ?>

        <?php if ($model != null AND $model->isJpt()) { ?>
            <i>Lampiran SKP hanya untuk Non JPT</i>
        <?php } ?>

        <?php if ($model != null AND $model->isJpt() == false) { ?>
            <?= $this->render('_table-lampiran', [
                'model' => $model,
            ]) ?>
        <?php } ?>

    </div>

</div>
