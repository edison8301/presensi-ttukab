<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SkpNilai */

$this->title = "Tambah Skp Nilai";
$this->params['breadcrumbs'][] = ['label' => 'Skp Nilais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-nilai-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
