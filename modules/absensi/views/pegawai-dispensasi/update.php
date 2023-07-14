<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\PegawaiDispensasi */
/* @var $referrer string */

$this->title = "Sunting Pegawai Dispensasi";
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Dispensasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pegawai-dispensasi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
