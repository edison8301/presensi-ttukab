<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstansiRekapAbsensi */
/* @var $referrer string */

$this->title = "Sunting Instansi Rekap Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Rekap Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="instansi-rekap-absensi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
