<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\InstansiKordinatif */
/* @var $referrer string */


$this->title = "Tambah Instansi Kordinatif";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Kordinatifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-kordinatif-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
