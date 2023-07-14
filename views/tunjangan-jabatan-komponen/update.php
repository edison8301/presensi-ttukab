<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganJabatanKomponen */
/* @var $referrer string */

$this->title = "Sunting Tunjangan Jabatan Komponen";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Jabatan Komponens', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="tunjangan-jabatan-komponen-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
