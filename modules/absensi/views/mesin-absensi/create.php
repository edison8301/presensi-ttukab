<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\MesinAbsensi */
/* @var $referrer string */

$this->title = "Tambah Mesin Absensi";
$this->params['breadcrumbs'][] = ['label' => 'Mesin Absensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mesin-absensi-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
