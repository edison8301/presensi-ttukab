<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PengaturanBerlaku */

$this->title = "Tambah Pengaturan Berlaku";
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Berlakus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-berlaku-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
