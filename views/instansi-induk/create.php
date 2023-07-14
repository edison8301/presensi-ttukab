<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InstansiInduk */

$this->title = "Tambah Instansi Induk";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Induks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-induk-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
