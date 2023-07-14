<?php

/* @var $level int */
/* @var $jabatan \app\models\Jabatan */
/* @var $this \yii\web\View */

use app\models\User;
use yii\helpers\Html;


$jabatan->tahun = User::getTahun();
$jabatan->bulan = $searchModel->bulan;

?>

<tr>
    <td style="padding-left: <?= (5 + $level*10); ?>px"><?= $jabatan->namaJabatan; ?> <?= $jabatan->getIconIdJabatanEvjab(); ?></td>
    <td style="text-align: center">
        <?= @$jabatan->instansiBidang->nama; ?>
    </td>
    <td style="text-align: center;">
        <?= @$jabatan->getJenisJabatan(); ?>
        <?php if($jabatan->status_kepala==1) { print "<br/>(Kepala)"; }; ?>
    </td>
    <td style="text-align: center;">
        <?= @$jabatan->nilai_jabatan; ?>
    </td>
    <td style="text-align: center;">
        <?= @$jabatan->kelas_jabatan; ?>
    </td>
    <td>
        <ul>
            <?php foreach ($jabatan->manyInstansiPegawaiByBulanTahun as $instansiPegawai) { ?>
                <li>
                    <?= str_replace('-', '<br>', @$instansiPegawai->pegawai->namaNip);?>
                </li>
            <?php } ?>
        </ul>
    </td>
    <?php /*
    <td style="text-align: center;">
        <?= $jabatan->countInstansiPegawai(); ?>
    </td>
    */ ?>
    <td style="text-align: center;">
        <?= $jabatan->getTextStatusVerifikasi(); ?>
    </td>
</tr>

<?php $level++; foreach($jabatan->findAllSub() as $subjabatan) { ?>
    <?= $this->render('_tr-jabatan-pdf', [
            'jabatan' => $subjabatan,
            'level' => $level,
            'searchModel' => $searchModel
    ]); ?>
<?php } ?>
