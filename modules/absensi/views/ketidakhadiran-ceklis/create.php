<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\KetidakhadiranCeklis */
/* @var $referrer string */

$this->title = "Tambah Ketidakhadiran Ceklis";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadiran Ceklis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-ceklis-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
