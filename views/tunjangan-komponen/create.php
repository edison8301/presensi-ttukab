<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TunjanganKomponen */
/* @var $referrer string */

$this->title = "Tambah Tunjangan Komponen";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Komponens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-komponen-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
