<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ShiftKerja */

$this->title = 'Tambah Shift Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Shift Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-kerja-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
