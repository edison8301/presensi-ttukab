<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Kegiatan */

$this->title = "Tambah Kegiatan";
$this->params['breadcrumbs'][] = ['label' => 'Kegiatans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kegiatan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
