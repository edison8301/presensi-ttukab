<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KetidakhadiranCeklis */
/* @var $referrer string */

$this->title = "Sunting Ketidakhadiran Ceklis";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Ceklis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ketidakhadiran-ceklis-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
