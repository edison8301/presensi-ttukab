<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\KegiatanHarianDiskresi */

$this->title = "Tambah Kegiatan Harian Diskresi";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Harian Diskresis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-harian-diskresi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
