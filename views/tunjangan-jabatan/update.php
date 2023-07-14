<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TunjanganJabatan */
/* @var $referrer string */

$this->title = "Sunting Tunjangan Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="tunjangan-jabatan-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
