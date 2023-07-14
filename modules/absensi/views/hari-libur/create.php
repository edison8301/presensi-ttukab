<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\HariLibur */
/* @var $referrer string */

$this->title = 'Tambah Hari Libur';
$this->params['breadcrumbs'][] = ['label' => 'Hari Liburs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hari-libur-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer' => $referrer
    ]) ?>

</div>
