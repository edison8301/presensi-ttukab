<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\remote\models\UploadPresensiRemote */
/* @var $referrer string */

$this->title = "Tambah Upload Presensi Remote";
$this->params['breadcrumbs'][] = ['label' => 'Upload Presensi Remotes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="upload-presensi-remote-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
