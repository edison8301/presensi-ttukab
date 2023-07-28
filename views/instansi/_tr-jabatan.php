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
    <td style="text-align: center">
        <?= $jabatan->getTooltipInstansiBidang(); ?>
    </td>
    <td style="text-align: center;">
        <?= @$jabatan->getJenisJabatan(); ?>
        <?php if($jabatan->status_kepala==1) { print "<br/>(Kepala)"; }; ?>
        <?= $jabatan->getLabelTingkatanStruktural(); ?>
        <?= $jabatan->getLabelTingkatanFungsional(); ?>
    </td>
    <?php if ($searchModel->mode != 'abk') { ?>
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
        <td style="text-align: center;">
            <?php if (User::isAdmin() || User::isMapping()) { ?>
                <?= @$jabatan->getEditableStatusVerifikasi(); ?>
            <?php } else { ?>
                <?= @$jabatan->getTextStatusVerifikasi() ?>
            <?php } ?>
        </td>
    <?php } ?>
    <?php if ($searchModel->mode == 'abk') { ?>
        <td style="text-align: center;">
            <?= $jumlahPegawai = count($jabatan->manyInstansiPegawaiByBulanTahun) ?>
        </td>
        <td style="text-align: center;">
            <?= $jabatan->getEditableHasilAbk() ?>
        </td>
        <td style="text-align: center;">
            <?php $selisih = $jumlahPegawai - $jabatan->hasil_abk ?>
            <?php if ($selisih > 0) { ?>
                +<?= $selisih ?>
            <?php } ?>
            <?php if ($selisih <= 0) { ?>
                <?= $selisih ?>
            <?php } ?>
        </td>
    <?php } ?>
    <td style="text-align: center;">
        <?php if (User::isAdmin() || User::isMapping()) { ?>
        <?php /* Html::a('<i class="glyphicon glyphicon-plus"></i>', [
                '/jabatan/create',
                'id_instansi'=>$jabatan->id_instansi,
                'id_instansi_bidang'=>$jabatan->id_instansi_bidang,
                'id_induk' => $jabatan->id
        ], ['data-toggle' => 'tooltip', 'title' => 'Tambah Sub Jabatan']); */ ?>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/jabatan/update', 'id' => $jabatan->id], ['data-toggle' => 'tooltip', 'title' => 'Edit']); ?>
        <?php /* Html::a('<i class="glyphicon glyphicon-trash"></i>', ['/jabatan/delete', 'id' => $jabatan->id], [
            'data' => [
                'toggle' => 'tooltip',
                'method' => 'post',
                'confirm' => 'Yakin akan menhapus jabatan?'
            ],
            'title' => 'Hapus',
        ]); */ ?>
        <?php } ?>
    </td>
</tr>

<?php 
    $allJabatanSub = $jabatan->findAllSub([
        'status_tampil' => $searchModel->status_tampil,
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
