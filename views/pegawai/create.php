<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pegawai */
/* @var $referrer string */

$this->title = "Tambah Pegawai";
$this->params['breadcrumbs'][] = ['label' => 'Pegawais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pegawai-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
