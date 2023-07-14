<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpIkiMik */

$this->title = "Tambah Manual Indikator Kinerja";
$this->params['breadcrumbs'][] = ['label' => 'Skp Iki Miks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-iki-mik-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
