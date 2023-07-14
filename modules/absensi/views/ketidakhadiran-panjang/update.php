<?php

use app\modules\absensi\models\KetidakhadiranPanjang;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model KetidakhadiranPanjang*/
/* @var $referrer mixed|null|string */

$this->title = 'Sunting Ketidakhadiran Hari Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Ketidakhadiran Panjang', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="ketidakhadiran-panjang-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
