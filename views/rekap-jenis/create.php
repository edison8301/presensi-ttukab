<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RekapJenis */

$this->title = "Tambah Rekap Jenis";
$this->params['breadcrumbs'][] = ['label' => 'Rekap Jenis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rekap-jenis-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
