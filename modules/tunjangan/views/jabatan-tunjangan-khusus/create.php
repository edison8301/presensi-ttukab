<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\tunjangan\models\JabatanTunjanganKhusus */
/* @var $referrer string */

$this->title = "Tambah Jabatan Tunjangan Khusus";
$this->params['breadcrumbs'][] = ['label' => 'Jabatan Tunjangan Khususes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jabatan-tunjangan-khusus-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referrer'=> $referrer
    ]) ?>

</div>
