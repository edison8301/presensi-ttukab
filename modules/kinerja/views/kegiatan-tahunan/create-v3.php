<?php

/* @var $this yii\web\View */
/* @var $model \app\modules\kinerja\models\KegiatanTahunan */
/* @var $referrer string */

$this->title = "Tambah Indikator Kinerja Individu";
$this->params['breadcrumbs'][] = ['label' => 'IKI', 'url' => ['index-v3']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-tahunan-create">

    <?= $this->render('_form-v3', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
