<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InstansiBidang */
/* @var $referrer string */

$this->title = "Tambah Instansi Bidang";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Bidangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-bidang-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
