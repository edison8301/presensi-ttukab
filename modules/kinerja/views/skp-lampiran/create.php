<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\kinerja\models\SkpLampiran */

$this->title = "Tambah SKP Lampiran";
$this->params['breadcrumbs'][] = ['label' => 'Skp Lampirans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skp-lampiran-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
