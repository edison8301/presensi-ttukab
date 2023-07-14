<?php

use app\modules\absensi\models\KetidakhadiranPanjang;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model KetidakhadiranPanjang */
/* @var $referrer mixed|null|string */

$this->title = 'Tambah Ketidakhadiran Hari Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Ketidakhadiran Hari Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ketidakhadiran-panjang-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
