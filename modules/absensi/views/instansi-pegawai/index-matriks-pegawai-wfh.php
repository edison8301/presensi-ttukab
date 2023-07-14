<?php

use app\models\PegawaiWfh;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\absensi\models\InstansiPegawaiSearch */
/* @var $datetime DateTime */

$this->title = 'Matriks Pegawai WFH';

$this->params['breadcrumbs'][] = $this->title;

$tanggalAkhir = $datetime->format('t');

$bulan = $searchModel->bulan;

if($bulan==null)$bulan = date('m');

if(strlen($bulan)==1) $bulan = '0'.$bulan;

?>

<?= $this->render('_filter-index', [
    'searchModel' => $searchModel,
    'action' => ['/instansi-pegawai/index-matriks-shift-kerja']
]); ?>

<div class="instansi-pegawai-index-daftar box box-default">
    <div class="box-header">

    </div>
    <div class="box-body">
        <div style="overflow-x: auto;">
            <table class="table table-bordered table-condensed">
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">No</th>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Pegawai</th>
                    <th colspan="<?= $tanggalAkhir ?>" style="text-align: center; vertical-align: middle;">Tanggal</th>
                </tr>
                <tr>
                    <?php for($i=1; $i <= $tanggalAkhir; $i++) { ?>
                        <th style="text-align: center; width: 200px;"><?= $i ?></th>
                    <?php } ?>
                </tr>
                <?php if($searchModel->id_instansi != null) { ?>
                    <?php $no=$pagination->offset+1; foreach ($allInstansiPegawai as $instansiPegawai) { ?>
                        <?php
                            if(@$instansiPegawai->pegawai === null) {
                                continue;
                            }

                            $query = PegawaiWfh::find()
                                ->andWhere(['id_pegawai'=>$instansiPegawai->id_pegawai])
                                ->andWhere('tanggal >= :tanggal_awal AND tanggal <= :tanggal_akhir',[
                                    ':tanggal_awal' => $datetime->format('Y-m-01'),
                                    ':tanggal_akhir' => $datetime->format('Y-m-t')
                                ]);
                            $arrayPegawaiWfh = $query->all();
                        ?>
                        <tr>
                            <td style="text-align: center;"><?= $no ?></td>
                            <td>
                                <?= @$instansiPegawai->pegawai->nama; ?><br>
                                NIP.<?= @$instansiPegawai->pegawai->nip ?>
                            </td>
                            <?php for($i=1; $i<=$tanggalAkhir; $i++) { ?>
                                <?php
                                    $tanggal = $datetime->format('Y-m').'-'.$i;
                                    $datetimeFor = DateTime::createFromFormat('Y-n-j', $tanggal);
                                    $pegawaiWfh = PegawaiWfh::findOneFromArray([
                                        'id_pegawai' => $instansiPegawai->id_pegawai,
                                        'tanggal' => $datetimeFor->format('Y-m-d')
                                    ], $arrayPegawaiWfh);
                                ?>
                                <td style="text-align: center">
                                    <?php if($editable == true) { ?>
                                    <?= Editable::widget([
                                        'model'	 => $pegawaiWfh,
                                        'name' => 'status_aktif',
                                        'value' => @$pegawaiWfh->status_aktif,
                                        'displayValue' => @$pegawaiWfh->namaStatusAktif,
                                        'valueIfNull' =>  'WFO',
                                        'header' => 'WFH',
                                        'formOptions' => ['action' => ['/pegawai-wfh/create-or-update-editable']],
                                        'beforeInput' => Html::hiddenInput('editableKey', @$pegawaiWfh->id)
                                            .Html::hiddenInput('tanggal', $datetimeFor->format('Y-m-d'))
                                            .Html::hiddenInput('id_pegawai', $instansiPegawai->id_pegawai),
                                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                        'data' => ['1' => 'WFH','0' => 'WFO'],
                                        'placement' => 'top',
                                        'options' => ['placeholder' => 'WFH'],
                                        'displayValueConfig' => ['1' => 'WFH','0' => 'WFO'],
                                    ]) ?>
                                    <?php } else { ?>
                                        <?= @$pegawaiWfh->status_aktif == 1 ? "WFH" : "WFO"; ?>
                                    <?php } ?>
                                </td>
                                <?php } ?>
                        </tr>
                    <?php $no++; } ?>
                <?php } ?>
            </table>
            <?= LinkPager::widget([
                'pagination' => $pagination,
            ]); ?>
        </div>
    </div>
</div>
