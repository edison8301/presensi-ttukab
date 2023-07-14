<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpIkiMik */

$this->title = "Sunting Manual Indikator Kinerja";
$this->params['breadcrumbs'][] = ['label' => 'Skp Iki Miks', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="skp-iki-mik-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
