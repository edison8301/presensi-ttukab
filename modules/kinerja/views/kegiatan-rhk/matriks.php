<?php

use app\components\Helper;
use app\components\Session;
use app\modules\kinerja\models\KegiatanRhk;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\kinerja\models\KegiatanRhkSearch */
/* @var $allKegiatanRhkUtama \app\modules\kinerja\models\KegiatanRhk[] */
/* @var $allKegiatanRhkTambahan \app\modules\kinerja\models\KegiatanRhk[] */

$this->title = 'Matriks Tahun ' . Session::getTahun();
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('//filter/_filter-tahun'); ?>

<?= $this->render('_filter-index', [
    'searchModel' => $searchModel,
]) ?>

<div class="box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title">Matriks Target Bulanan SKP</h3>
    </div>

    <div class="box-body">

        <?php if ($searchModel->id_pegawai == null) { ?>
            <i>Silahkan pilih pegawai terlebih dahulu.</i>
        <?php } ?>

        <?php if ($searchModel->id_pegawai != null) { ?>
            <?= $this->render('_table-matriks', [
                'allKegiatanRhkUtama' => $allKegiatanRhkUtama,
                'allKegiatanRhkTambahan' => $allKegiatanRhkTambahan,
            ]) ?>
        <?php } ?>

    </div>

</div>
