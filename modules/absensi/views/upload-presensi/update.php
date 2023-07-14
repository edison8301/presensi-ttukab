<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensi */
/* @var $referrer string */

$this->title = "Sunting Upload Presensi";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensis', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Sunting';
?>
<div class="upload-presensi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
