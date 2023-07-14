<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\ShiftKerjaReguler */
/* @var $referrer string */

$this->title = "Tambah Shift Kerja Reguler";
$this->params['breadcrumbs'][] = ['label' => 'Shift Kerja Regulers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shift-kerja-reguler-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
