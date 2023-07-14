<?php

/* @var $tahun int */
/* @var $bulan int */

$query = \app\models\Pegawai::find();

?>

<?php $list=[]; foreach($query->all() as $pegawai) { ?>

    <?php $jumlah = $pegawai->getCountPegawaiRekapAbsensi([
        'bulan'=>$bulan,
        'tahun'=>$tahun
    ]); ?>

    <?php if($jumlah > 1) {
        print $pegawai->id.' - '.$pegawai->nama.' - '.$pegawai->nip.' - '.$jumlah.'<br/>';
        $list[]=$pegawai->id;
    } ?>

<?php } ?>

<?php print implode(',',$list);




