<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiDispensasi */
/* @var $referrer string */

$this->title = "Tambah Pegawai Dispensasi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Dispensasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-dispensasi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
