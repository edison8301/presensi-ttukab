<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKelasKomponen */
/* @var $referrer string */

$this->title = "Tambah Tunjangan Kelas Komponen";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Kelas Komponens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-kelas-komponen-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
