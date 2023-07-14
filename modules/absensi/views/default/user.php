<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $user backend\kinerja\models\User */

$this->title = "Detail User";
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title"><?= $user->nip; ?></h3>
    </div>

    <div class="box-body">

        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'nama',
                'nip'
            ],
        ]) ?>
    </div>

    <div class="box-footer">
        <?= Html::a('Set No ID Absensi',['/kinerja/user/set-no-id-absensi','id'=>$user->id],['class'=>'btn btn-flat btn-warning']); ?>
    </div>

</div>

<div class="user-view box box-primary">

    <div class="box-header">
        <h3 class="box-title">Daftar Absensi</h3>
    </div>

    <div class="box-body">
        <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th style="text-align: center">Tanggal</th>
            <th style="text-align: center">Hari</th>
            <th style="text-align: center">Total Absensi</th>
            <th style="text-align: center">Telat</th>
            <th style="text-align: center">DL</th>
            <th style="text-align: center">S</th>
            <th style="text-align: center">I</th>
            <th style="text-align: center">C</th>
            <th style="text-align: center">TK</th>
        </tr>
        </thead>
        <?php
            $dateTime = date_create(User::getTahun().'-'.User::getBulan());
            $tanggal = $dateTime;
        ?>
        <?php for($i=1;$i<=$dateTime->format('t');$i++) { ?>
        <?php
            $class = "";
            if($dateTime->format('N')==6 OR $dateTime->format('N')==7)
                $class = 'danger';
        ?>
        <tr class="<?= $class; ?>">
            <td style="text-align: center"><?= $tanggal->format('Y-m-d'); ?></td>
            <td>&nbsp;</td>
            <td style="text-align: center"><?= $user->countAbsensi(['tanggal'=>$tanggal->format('Y-m-d')]); ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php $tanggal->modify('+1 day'); } ?>
        <?php $i=1; foreach($user->findAllAbsensi(['tahun'=>User::getTahun(),'bulan'=>User::getBulan()]) as $data) { ?>

        <?php $i++; } ?>
        </table>
    </div>

</div>
