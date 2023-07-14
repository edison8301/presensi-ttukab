<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PengaturanBerlaku */

$this->title = "Sunting Pengaturan Berlaku";
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Berlakus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="pengaturan-berlaku-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
