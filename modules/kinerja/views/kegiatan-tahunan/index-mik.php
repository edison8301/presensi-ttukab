<?php

/* @var $this \yii\web\View */
/* @var $allKegiatanTahunan app\modules\kinerja\models\KegiatanTahunan[] */

$this->title = 'Indikator Kinerja Individu';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title ?></h3>
    </div>
    <div class="box-body">

        <table class="table table-bordered">
            <tr>
                <th style="text-align:center; width: 50px;">No</th>
                <th>Indikator Kinerja Individu</th>
                <th style="width: 50px;"></th>
            </tr>
            <?php $no=1; foreach ($allKegiatanTahunan as $kegiatanTahunan) { ?>
                <?= $this->render('_tr-index-mik', [
                    'model' => $kegiatanTahunan,
                    'no' => $no++,
                ]); ?>
            <?php } ?>
        </table>

    </div>
</div>
