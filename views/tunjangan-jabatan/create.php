<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TunjanganJabatan */
/* @var $referrer string */

$this->title = "Tambah Tunjangan Jabatan";
$this->params['breadcrumbs'][] = ['label' => 'Tunjangan Jabatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tunjangan-jabatan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
