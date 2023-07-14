<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganPelaksana */
/* @var $referrer string */

$this->title = "Tambah Jabatan Tunjangan Pelaksana";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Pelaksanas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-pelaksana-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
