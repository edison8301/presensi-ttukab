<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tukin\models\InstansiSerapanAnggaran */
/* @var $referrer string */


$this->title = "Tambah Instansi Serapan Anggaran";
$this->params['breadcrumbs'][] = ['label' => 'Instansi Serapan Anggarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instansi-serapan-anggaran-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
