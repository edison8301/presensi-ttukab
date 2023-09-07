<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14/11/2018
 * Time: 12:02
 */
/* @var $level int */
/* @var $jabatan \app\models\Jabatan */
/* @var $this \yii\web\View */
/* @var $searchModel \app\models\InstansiSearch|\app\modules\tukin\models\PegawaiSearch|\app\modules\tukin\models\RefVariabelObjektifSearch */

use app\models\Jabatan;
use app\models\User;
use yii\helpers\Html;


$jabatan->tahun = User::getTahun();
$jabatan->bulan = $searchModel->bulan;

?>

<tr>
    <td style="padding-left: <?= (8 + $level*20); ?>px">
        <span data-toggle="tooltip" title="<?= "Atasan : ".@$jabatan->jabatanInduk->namaJabatan; ?>"><?= $jabatan->getNamaJabatan(); ?></span>
        <?= $jabatan->getIconIdJabatanEvjab(); ?>
        <?php if ($jabatan->status_kepala == 1 AND @$jabatan->jabatanInduk != null) { ?>
            <br/>Atasan: <?= @$jabatan->jabatanInduk->namaJabatan; ?>
        <?php } ?>
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
        <ul style="padding-left:20px">
            <?php foreach ($jabatan->manyInstansiPegawaiByBulanTahun as $instansiPegawai) { ?>
                <li>
                    <?php if (User::isAdmin() || User::isMapping()) { ?>
                        <?= Html::a(str_replace('-', '<br>', @$instansiPegawai->getNamaPegawai()),['/pegawai/view','id'=>@$instansiPegawai->pegawai->id]) ?>
                    <?php } else { ?>
                        <?= str_replace('-', '<br>', @$instansiPegawai->getNamaPegawai()) ?>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </td>
    <td style="text-align: center;">
        <?php if (User::isAdmin() || User::isMapping()) { ?>
            <?= Html::a($jabatan->countInstansiPegawai(),[
                '/instansi-pegawai/index',
                'InstansiPegawaiSearch[id_jabatan]'=>$jabatan->id
            ]); ?>
        <?php } else { ?>
            <?= $jabatan->countInstansiPegawai() ?>
        <?php } ?>
    </td>
</tr>

<?php 
    $allJabatanSub = $jabatan->findAllSub([
        'id_instansi' => @$id_instansi,
    ]);
?>
<?php $level++; foreach($allJabatanSub as $subjabatan) { ?>
    <?= $this->render('_tr-jabatan', [
        'jabatan' => $subjabatan,
        'level' => $level,
        'searchModel' => $searchModel,
        'id_instansi' => @$id_instansi,
    ]); ?>
<?php } ?>
