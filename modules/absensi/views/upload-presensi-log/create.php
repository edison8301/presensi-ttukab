<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\absensi\models\UploadPresensiLog */
/* @var $referrer string */

$this->title = "Tambah Upload Presensi Log";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensi Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-log-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
