<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Catatan */

$this->title = "Tambah Kegiatan Tahunan Catatan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Tahunan Catatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catatan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
