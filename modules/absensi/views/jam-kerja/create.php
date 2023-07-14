<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\JamKerja */

$this->title = 'Tambah Jam Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Jam Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jam-kerja-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
