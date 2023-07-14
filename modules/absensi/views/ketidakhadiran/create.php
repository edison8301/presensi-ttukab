<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\Ketidakhadiran */
/* @var $referrer string */

$this->title = "Tambah Ketidakhadiran";
$this->params['breadcrumbs'][] = ['label' => 'Ketidakhadirans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
