<?php

use app\components\Session;
use app\models\PegawaiNonTppJenis;
use app\models\UserRole;
use app\modules\kinerja\models\KegiatanStatus;
use dmstr\widgets\Menu;

/* @var $this \yii\web\View */



if(Yii::$app->params['mode']=='tukin') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/absensi/dasbor/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'bank', 'items' => [
                ['label' => 'Aktivitas Absensi', 'url' => ['/absensi/instansi/index']],
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/instansi-rekap-absensi/index']],
                ['label' => 'Absensi Manual', 'url' => ['/absensi/instansi-absensi-manual/index', 'mode' => 1]],
                ['label' => 'Mesin Absensi', 'url' => ['/absensi/instansi/index-mesin-absensi']],
            ]],
            ['label' => 'Pegawai', 'icon' => 'user', 'items' => [
                ['label' => 'Pegawai', 'url' => ['/absensi/instansi-pegawai/index-pegawai']],
                ['label' => 'Shift Kerja', 'url' => ['/absensi/pegawai/index-shift-kerja']],
                ['label' => 'Batas Pengajuan', 'url' => ['/absensi/pegawai/index-batas-pengajuan']],
                ['label' => 'Kirim Ke Mesin', 'url' => ['/absensi/pegawai/kirim-ke-mesin']],
                ['label' => 'Userinfo', 'url' => ['/absensi/pegawai/index-userinfo']],
                ['label' => 'Fingerprint', 'url' => ['/absensi/pegawai/index-template']],
                ['label' => 'Pegawai Dispensasi', 'url' => ['/absensi/pegawai-dispensasi/index']],
                ['label' => 'Cek Dobel Rekap', 'url' => ['/absensi/pegawai-rekap-absensi/index']],
                ['label' => 'Rekap Abensi Peta', 'url' => ['/absensi/instansi-pegawai/index-rekap-peta']],
            ]],
            /*
            ['label' => 'Absensi Manual', 'icon' => 'book', 'items' => [
                ['label' => 'Perangkat Daerah', 'icon' => 'circle-o', 'url' => ['/absensi/instansi-absensi-manual/index', 'mode' => 2]],
                ['label' => 'Pegawai', 'icon' => 'circle-o', 'url' => ['/absensi/pegawai-absensi-manual/index']],
            ]],
            */
            ['label' => 'Monitoring', 'icon' => 'desktop', 'items' => [
                ['label' => 'Izin', 'url' => ['/absensi/pegawai-rekap-absensi/izin']],
                ['label' => 'Sakit', 'url' => ['/absensi/pegawai-rekap-absensi/sakit']],
                ['label' => 'Cuti', 'url' => ['/absensi/pegawai-rekap-absensi/cuti']],
                ['label' => 'Dinas Luar', 'url' => ['/absensi/pegawai-rekap-absensi/dinas-luar']],
                ['label' => 'Tugas Belajar', 'url' => ['/absensi/pegawai-rekap-absensi/tugas-belajar']],
                ['label' => 'Tugas Kedinasan', 'url' => ['/absensi/pegawai-rekap-absensi/tugas-kedinasan']],
                ['label' => 'Alasan Teknis', 'url' => ['/absensi/pegawai-rekap-absensi/alasan-teknis']],
                ['label' => 'Tanpa Keterangan', 'url' => ['/absensi/pegawai-rekap-absensi/tanpa-keterangan']],
            ]],
            //['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran/index']],
            ['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-panjang/index']],
            ['label' => 'Ketidakhadiran Jam Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-jam-kerja/index']],
            ['label' => 'Ketidakhadiran Kegiatan', 'icon' => 'list', 'items' => [
                ['label' => 'Upacara', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 1]],
                ['label' => 'Senam', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 2]],
                ['label' => 'Apel Pagi', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 3]],
                ['label' => 'Apel Sore', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 4]],
                ['label' => 'Sidak', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 5]],
            ]],
            ['label' => 'Hukuman Disiplin', 'icon'=>'gavel', 'url' => ['/absensi/hukuman-disiplin/index']],
            ['label' => 'Shift Kerja', 'icon' => 'refresh', 'url' => ['/absensi/shift-kerja/index']],
            ['label' => 'Shift Kerja Reguler', 'icon' => 'clock-o', 'url' => ['/absensi/shift-kerja-reguler/index']],
            // ['label' => 'Jadwal WFH', 'icon' => 'home', 'url' => ['/absensi/instansi-pegawai/index-matriks-pegawai-wfh']],
            ['label' => 'Hari Libur', 'icon' => 'calendar', 'url' => ['/absensi/hari-libur/index']],
            ['label' => 'IClock', 'icon' => 'clock-o', 'items' => [
                ['label' => 'Checkinout', 'icon' => 'circle-o', 'url' => ['/iclock/checkinout/index']],
                ['label' => 'Userinfo', 'icon' => 'circle-o', 'url' => ['/iclock/userinfo/index']],
                ['label' => 'Template', 'icon' => 'circle-o', 'url' => ['/iclock/template/index']],
                ['label' => 'IClock', 'icon' => 'circle-o', 'url' => ['/iclock/iclock/index']],
            ]],
            ['label' => 'Upload Presensi', 'icon' => 'wrench', 'items' => [
                ['label' => 'Upload Presensi Manual', 'icon' => 'circle-o', 'url' => ['/absensi/upload-presensi/index']],
                //['label' => 'Upload Presensi Manual', 'icon' => 'circle-o', 'url' => ['/remote/upload-presensi-remote/index']],
                ['label' => 'Daftar Mesin Absen', 'icon' => 'circle-o', 'url' => ['/absensi/mesin-absensi/index']],
            ]],
            ['label' => 'Perawatan', 'icon' => 'wrench', 'items' => [
                ['label' => 'Pegawai Tanpa Userinfo', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-userinfo']],
                ['label' => 'Pegawai Tanpa Checkinout', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-checkinout']],
                ['label' => 'Pegawai Tanpa Template', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-template']],
            ]],
            /*
            ['label' => 'KINERJA PP 46/2011', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/pegawai/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/instansi-pegawai/index']],
            ['label' => 'Rekap Kinerja', 'icon' => 'book', 'url' => ['/kinerja/pegawai-rekap-kinerja/index']],
            ['label' => 'IKI', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai/index-iki']],
            ['label' => 'SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index']],
            ['label' => 'Keg. Tahunan', 'icon' => 'address-book', 'items' => [
                ['label' => 'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view']],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'address-book', 'url' => ['/kinerja/kegiatan-bulanan/index']],
            ['label' => 'Keg. Harian', 'icon' => 'address-book', 'url' => ['/kinerja/kegiatan-harian/index']],
            ['label' => 'Pegawai Ubah Target', 'icon' => 'book', 'url' => ['/kinerja/pegawai-ubah/index']],
            ['label' => 'Jadwal', 'icon' => 'calendar', 'url' => ['/kinerja/jadwal-kegiatan-bulan/index']],
            ['label' => 'Rekap IKI', 'icon' => 'file', 'url' => ['/kinerja/instansi-pegawai/rekap-iki']],
            ['label' => 'Rekap SKP & RKB', 'icon' => 'file', 'url' => ['/kinerja/instansi-pegawai/index-rekap']],
            ['label' => 'KINERJA PP 30/2019', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/dashboard/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/instansi-pegawai/index-v2']],
            ['label' => 'Rekap Kinerja', 'icon' => 'book', 'url' => ['/instansi/index-rekap-kinerja', 'versi' => '30/2019']],
            ['label' => 'SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index-v2']],
            ['label' => 'Kinerja Tahunan','icon' => 'folder','items' => [
                ['label' => 'Daftar Kinerja', 'url' => ['/kinerja/kegiatan-tahunan/index-v2']],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view-v2']],
            ]],
            ['label' => 'Kinerja Bulanan', 'icon' => 'folder', 'url' => ['/kinerja/kegiatan-bulanan/index-v2']],
            ['label' => 'Kinerja Harian', 'icon' => 'folder', 'items' => [
                ['label' => 'Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-v3']],
                ['label' => 'Rekap Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-rekap-kegiatan-harian-v3']],
            ]],
            ['label' => 'PERMENPAN 6 TAHUN 2022', 'options' => ['class' => 'header', 'style' => 'color:yellow']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/instansi-pegawai/index-v3']],
            ['label' => 'Rekap Kinerja', 'icon' => 'book', 'url' => ['/instansi/index-rekap-kinerja']],
            ['label' => 'SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index-v3']],
            [
                'label' => 'Kinerja Tahunan',
                'icon' => 'folder',
                'url' => '#',
                'items' => [
                    ['label' => 'Kinerja Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-rhk/index']],
                    ['label' => 'Matriks Target/Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-rhk/matriks']],
                ]
            ],
            ['label' => 'Kinerja Bulanan', 'icon' => 'folder', 'url' => ['/kinerja/kegiatan-bulanan/index-v3']],
            ['label' => 'Kinerja Harian', 'icon' => 'folder', 'items' => [
                ['label' => 'Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-v4']],
                ['label' => 'Rekap Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-rekap-kegiatan-harian-v4']],
                ['label' => 'Diskresi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian-diskresi/index'], 'visible' => in_array(Session::getIdUser(), [5877, 5965])],
            ]],
            ['label' => 'SKP Nilai', 'icon' => 'book', 'url' => ['/skp-nilai/index']],
            ['label' => 'Matriks Peran Hasil', 'icon' => 'list', 'url' => ['/kinerja/instansi-pegawai/matriks-peran-hasil']],
            ['label' => 'TUKIN', 'options' => ['class' => 'header']],
            ['label' => 'Home', 'icon' => 'circle', 'url' => ['/tukin/admin/index']],
            ['label' => 'Pegawai', 'icon' => 'users', 'items' => [
                ['label' => 'Pegawai', 'icon' => 'circle-o', 'url' => ['/tunjangan/instansi-pegawai/index', 'jenis' => 'tpp']],
                ['label' => 'Penundaan TPP', 'icon' => 'circle-o', 'url' => ['/tunjangan/instansi-pegawai/index', 'jenis' => 'penundaan-tpp']],
            ]],
            ['label' => 'Monitoring', 'icon' => 'desktop', 'url' => ['/tunjangan/pegawai-rekap-bulan/index']],
            ['label' => 'Rekap Pegawai', 'icon' => 'list', 'url' => ['/tunjangan/instansi-pegawai/index-rekap-pegawai']],
            ['label' => 'Rekap Tunjangan', 'icon' => 'list', 'url' => ['/tukin/pegawai/rekap']],
            ['label' => 'Besaran TPP', 'icon' => 'usd', 'items' => [
                ['label' => 'Berdasarkan Pergub 2021', 'icon' => 'circle-o', 'url' => ['/tunjangan-instansi-jenis-jabatan-kelas/index']],
                ['label' => 'Jabatan Struktural', 'icon' => 'circle-o', 'url' => ['/tunjangan/jabatan-tunjangan-struktural/index']],
                ['label' => 'Jabatan Fungsional', 'icon' => 'circle-o', 'url' => ['/tunjangan/jabatan-tunjangan-fungsional/index', 'status_p3k' => 0]],
                ['label' => 'Jabatan Pelaksana', 'icon' => 'circle-o', 'url' => ['/tunjangan/jabatan-tunjangan-pelaksana/index']],
                ['label' => 'Jabatan Khusus', 'icon' => 'circle-o', 'url' => ['/tunjangan/jabatan-tunjangan-khusus/index', 'status_p3k' => 0]],
            ]],
            ['label' => 'Besaran TPP (P3K)', 'icon' => 'usd', 'items' => [
                ['label' => 'Jabatan Fungsional', 'icon' => 'circle-o', 'url' => ['/tunjangan/jabatan-tunjangan-fungsional/index', 'status_p3k' => 1]],
                ['label' => 'Jabatan Khusus', 'icon' => 'circle-o', 'url' => ['/tunjangan/jabatan-tunjangan-khusus/index', 'status_p3k' => 1]],
            ]],
            ['label' => 'Potongan TPP', 'icon' => 'scissors', 'items' => [
                ['label' => 'Jenis Potongan', 'icon' => 'list', 'url' => ['/tunjangan/tunjangan-potongan/index']],
                ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/tunjangan/tunjangan-potongan-pegawai/index']],
                ],
            ],
            ['label' => 'Tambahan TPP', 'icon' => 'plus', 'items' => [
                ['label' => 'Jabatan Khusus', 'url' => ['/tunjangan/jabatan-tunjangan-khusus-pegawai/index']],
            ]],
            ['label' => 'Instansi Kordinatif', 'icon' => 'building-o', 'url' => ['/tukin/instansi-kordinatif/index']],
            ['label' => 'Serapan Anggaran', 'icon' => 'building', 'url' => ['/tukin/instansi-serapan-anggaran/index']],
            ['label' => 'Referensi', 'icon' => 'database', 'items' => [
                ['label' => 'Variabel Objektif', 'url' => ['/tukin/ref-variabel-objektif/index']],
                ['label'=>'Tunjangan per Kelas','url' => ['/tunjangan-kelas/index']],
                ['label'=>'Tunjangan per Jabatan','url' => ['/tunjangan-jabatan/index']],
                ['label'=>'Komponen Tunjangan','url' => ['/tunjangan-komponen/index']],
            ]],
            */
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Berita', 'icon' => 'newspaper-o', 'url' => ['/artikel/index']],
            ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => ['/pengumuman/index']],
            ['label' => 'Pengaturan', 'icon' => 'wrench', 'url' => ['/pengaturan/index']],
            ['label' => 'Peta', 'icon' => 'map-marker', 'items' => [
                ['label' => 'Perangkat Daerah', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'instansi']],
                ['label' => 'Pegawai', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'pegawai']],
                ['label' => 'Kegiatan', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'kegiatan']],
                // ['label' => 'Pegawai (Rumah/WFH)', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'pegawai-wfh']],
                // ['label' => 'Khusus', 'icon' => 'circle-o', 'url' => ['/peta/index', 'mode' => 'khusus']],
            ]],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'items'=>[
                ['label'=>'Perangkat Daerah','url' => ['/instansi/index']],
                ['label'=>'Bidang','url' => ['/instansi-bidang/index']],
                ['label'=>'Lokasi','url' => ['/instansi/index-lokasi']],
                ['label'=>'Induk','url' => ['/instansi/index-induk']],
            ]],
            ['label' => 'Pegawai', 'icon' => 'list', 'items' => [
                ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
                /*
                ['label' => 'Pegawai Sertifikasi', 'icon' => 'graduation-cap','url' => ['/pegawai-sertifikasi/index']],
                ['label' => 'Pegawai Penghargaan', 'icon' => 'trophy','url' => ['/pegawai-penghargaan/index']],
                ['label' => 'Pegawai Cuti Ibadah', 'icon' => 'calendar','url' => ['/pegawai-cuti-ibadah/index']],
                ['label' => 'Pegawai Tugas Belajar', 'icon' => 'book','url' => ['/pegawai-tugas-belajar/index']],
                ['label' => 'Pegawai Penundaan TPP', 'icon' => 'clock-o','url' => ['/pegawai-tunda-bayar/index']],
                ['label' => 'Pegawai Seragam Dinas', 'icon' => 'gavel','url' => ['/pegawai-atribut/index']],
                ['label' => 'Pegawai Cuti Besar Non TPP', 'icon' => 'users','url' => ['/pegawai-non-tpp/index', 'id_pegawai_non_tpp_jenis' => PegawaiNonTppJenis::CUTI_BESAR]],
                ['label' => 'Pegawai Tubel Non TPP', 'icon' => 'users','url' => ['/pegawai-non-tpp/index', 'id_pegawai_non_tpp_jenis' => PegawaiNonTppJenis::TUGAS_BELAJAR]],
                */
            ]],
            ['label' => 'Grup Pegawai', 'icon' => 'users', 'url' => ['/grup/index']],
            ['label' => 'Jabatan', 'icon' => 'star', 'items'=>[
                ['label' => 'Jabatan', 'url' => ['/jabatan/index']],
                ['label' => 'Besar TPP', 'url' => ['/jabatan/index-tpp']],
                ['label' => 'Hasil Evjab', 'url' => ['/jabatan-evjab/index']],
            ]],
            ['label' => 'Mutasi dan Promosi', 'icon' => 'refresh', 'url' => ['/instansi-pegawai/index']],
            ['label' => 'Golongan Pegawai', 'icon' => 'refresh', 'url' => ['/pegawai-golongan/index']],
            ['label' => 'User', 'icon' => 'user', 'items' => [
                ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
                ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
                ['label' => 'Perangkat Daerah', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
                // ['label' => 'Verifikator', 'url' => ['/user/index', 'id_user_role' => UserRole::VERIFIKATOR]],
                // ['label' => 'Grup', 'url' => ['/user/index', 'id_user_role' => UserRole::GRUP]],
                // ['label' => 'Mapping', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING]],
                // ['label' => 'Admin PD', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN_INSTANSI]],
                // ['label' => 'Admin IKI', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN_IKI]],
                // ['label' => 'Operator Absen Manual', 'url' => ['/user/index', 'id_user_role' => UserRole::OPERATOR_ABSEN]],
                // ['label' => 'Operator Struktur Jabatan', 'url' => ['/user/index', 'id_user_role' => UserRole::OPERATOR_STRUKTUR]],
                // ['label' => 'Pemeriksa Absensi', 'url' => ['/user/index', 'id_user_role' => UserRole::PEMERIKSA_ABSENSI]],
                // ['label' => 'Pemeriksa Kinerja', 'url' => ['/user/index', 'id_user_role' => UserRole::PEMERIKSA_KINERJA]],
                // ['label' => 'Pemeriksa IKI', 'url' => ['/user/index', 'id_user_role' => UserRole::PEMERIKSA_IKI]],
                // ['label' => 'Mapping RPJMD', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING_RPJMD]],
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='skp-ckhp') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/dasbor/index']],
            ['label' => 'Rekap Kinerja', 'icon' => 'file-o', 'url' => ['/kinerja/pegawai-rekap-kinerja/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/pegawai/index']],
            ['label' => 'Keg. Tahunan', 'icon' => 'list', 'items' => [
                ['label' => 'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view']],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
            ['label' => 'Keg. Harian', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-harian/index']],
            ['label' => 'Rekap SKP & CKHP', 'icon' => 'file', 'url' => ['/kinerja/kegiatan-harian/rekap']],
            /*['label' => 'Tunjangan', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/tunjangan/dasbor/admin']],*/

            //['label' => 'MENU TUNJANGAN', 'options' => ['class' => 'header']],
            //['label' => 'Dashboard', 'icon' => 'home', 'url' => ['/site/index']],
            //['label' => 'ICLOCK', 'options' => ['class' => 'header']],
            //['label' => 'Checkinout', 'icon' => 'home', 'url' => ['/iclock/checkinout/index']],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => ['/pengumuman/index']],
            ['label' => 'Pengaturan', 'icon' => 'wrench', 'url' => ['/pengaturan/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'url' => ['/instansi/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
            ['label' => 'Grup Pegawai', 'icon' => 'users', 'url' => ['/grup/index']],
            ['label' => 'Jabatan', 'icon' => 'star', 'url' => ['/jabatan/index']],
            ['label' => 'Mutasi', 'icon' => 'refresh', 'url' => ['/pegawai-instansi/index']],
            ['label' => 'User', 'icon' => 'user', 'items' => [
                ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
                ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
                ['label' => 'Perangkat Daerah', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
                ['label' => 'Verifikator', 'url' => ['/user/index', 'id_user_role' => UserRole::VERIFIKATOR]],
                ['label' => 'Grup', 'url' => ['/user/index', 'id_user_role' => UserRole::GRUP]],
                ['label' => 'Mapping', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING]],
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='absensi') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/absensi/dasbor/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'bank', 'items' => [
                ['label' => 'Aktivitas Absensi', 'url' => ['/absensi/instansi/index']],
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/instansi-rekap-absensi/index']],
                ['label' => 'Mesin Absensi', 'url' => ['/absensi/instansi/index-mesin-absensi']],
            ]],
            ['label' => 'Pegawai', 'icon' => 'user', 'items' => [
                ['label' => 'Pegawai', 'url' => ['/absensi/pegawai/index']],
                ['label' => 'Rekap Absensi', 'url' => ['/absensi/pegawai-rekap-absensi/index']],
                ['label' => 'Shift Kerja', 'url' => ['/absensi/pegawai/index-shift-kerja']],
                ['label' => 'Absensi Manual', 'url' => ['/absensi/pegawai/index-absensi-manual']],
                ['label' => 'Batas Pengajuan', 'url' => ['/absensi/pegawai/index-batas-pengajuan']],
                ['label' => 'Userinfo', 'url' => ['/absensi/pegawai/index-userinfo']],
                ['label' => 'Fingerprint', 'url' => ['/absensi/pegawai/index-template']],
                ['label' => 'Pegawai Dispensasi', 'url' => ['/absensi/pegawai-dispensasi/index']],
            ]],
            ['label' => 'Monitoring', 'icon' => 'desktop', 'items' => [
                ['label' => 'Izin', 'url' => ['/absensi/pegawai-rekap-absensi/izin']],
                ['label' => 'Sakit', 'url' => ['/absensi/pegawai-rekap-absensi/sakit']],
                ['label' => 'Cuti', 'url' => ['/absensi/pegawai-rekap-absensi/cuti']],
                ['label' => 'Dinas Luar', 'url' => ['/absensi/pegawai-rekap-absensi/dinas-luar']],
                ['label' => 'Tugas Belajar', 'url' => ['/absensi/pegawai-rekap-absensi/tugas-belajar']],
                ['label' => 'Tugas Kedinasan', 'url' => ['/absensi/pegawai-rekap-absensi/tugas-kedinasan']],
                ['label' => 'Alasan Teknis', 'url' => ['/absensi/pegawai-rekap-absensi/alasan-teknis']],
                ['label' => 'Tanpa Keterangan', 'url' => ['/absensi/pegawai-rekap-absensi/tanpa-keterangan']],
            ]],
            ['label' => 'Ketidakhadiran Hari Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran/index']],
            ['label' => 'Ketidakhadiran Jam Kerja', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-jam-kerja/index']],
            ['label' => 'Ketidakhadiran Kegiatan', 'icon' => 'list', 'items' => [
                ['label' => 'Upacara', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 1]],
                ['label' => 'Senam', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 2]],
                ['label' => 'Apel Pagi', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 3]],
                ['label' => 'Apel Sore', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 4]],
                ['label' => 'Sidak', 'url' => ['/absensi/ketidakhadiran-kegiatan/index', 'id_ketidakhadiran_kegiatan_jenis' => 5]],
            ]],
            ['label' => 'Ketidakhadiran Panjang', 'icon' => 'list', 'url' => ['/absensi/ketidakhadiran-panjang/index']],
            ['label' => 'Hukuman Disiplin', 'icon' => 'gavel', 'url' => ['/absensi/hukuman-disiplin/index']],
            ['label' => 'Shift Kerja', 'icon' => 'refresh', 'url' => ['/absensi/shift-kerja/index']],
            ['label' => 'Shift Kerja Reguler', 'icon' => 'clock-o', 'url' => ['/absensi/shift-kerja-reguler/index']],
            ['label' => 'Hari Libur', 'icon' => 'calendar', 'url' => ['/absensi/hari-libur/index']],
            ['label' => 'IClock', 'icon' => 'clock-o', 'items' => [
                ['label' => 'Checkinout', 'icon' => 'circle-o', 'url' => ['/iclock/checkinout/index']],
                ['label' => 'Userinfo', 'icon' => 'circle-o', 'url' => ['/iclock/userinfo/index']],
                ['label' => 'Template', 'icon' => 'circle-o', 'url' => ['/iclock/template/index']],
                ['label' => 'IClock', 'icon' => 'circle-o', 'url' => ['/iclock/iclock/index']],
            ]],
            ['label' => 'Upload Presensi', 'icon' => 'wrench', 'items' => [
                ['label' => 'Upload Presensi Manual', 'icon' => 'circle-o', 'url' => ['/absensi/upload-presensi/index']],
                ['label' => 'Daftar Mesin Absen', 'icon' => 'circle-o', 'url' => ['/absensi/mesin-absensi/index']],
            ]],
            ['label' => 'Perawatan', 'icon' => 'wrench', 'items' => [
                ['label' => 'Pegawai Tanpa Userinfo', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-userinfo']],
                ['label' => 'Pegawai Tanpa Checkinout', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-checkinout']],
                ['label' => 'Pegawai Tanpa Template', 'icon' => 'circle-o', 'url' => ['/absensi/perawatan/pegawai-tanpa-template']],
            ]],
            ['label' => 'KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/kinerja/dasbor/index']],
            ['label' => 'Rekap Kinerja', 'icon' => 'file-o', 'url' => ['/kinerja/pegawai-rekap-kinerja/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/kinerja/pegawai/index']],
            ['label' => 'Keg. Tahunan', 'icon' => 'list', 'items' => [
                ['label' => 'Daftar Kegiatan', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                ['label' => 'Daftar SKP', 'url' => ['/kinerja/instansi-pegawai/view']],
            ]],
            ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
            ['label' => 'Keg. Harian', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-harian/index']],
            ['label' => 'Rekap SKP & CKHP', 'icon' => 'file', 'url' => ['/kinerja/kegiatan-harian/rekap']],
            /*['label' => 'Tunjangan', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/tunjangan/dasbor/admin']],*/

            //['label' => 'MENU TUNJANGAN', 'options' => ['class' => 'header']],
            //['label' => 'Dashboard', 'icon' => 'home', 'url' => ['/site/index']],
            //['label' => 'ICLOCK', 'options' => ['class' => 'header']],
            //['label' => 'Checkinout', 'icon' => 'home', 'url' => ['/iclock/checkinout/index']],
            ['label' => 'MENU SISTEM', 'options' => ['class' => 'header']],
            ['label' => 'Pengumuman', 'icon' => 'bullhorn', 'url' => ['/pengumuman/index']],
            ['label' => 'Pengaturan', 'icon' => 'wrench', 'url' => ['/pengaturan/index']],
            ['label' => 'Perangkat Daerah', 'icon' => 'building', 'url' => ['/instansi/index']],
            ['label' => 'Pegawai', 'icon' => 'user', 'url' => ['/pegawai/index']],
            ['label' => 'Grup Pegawai', 'icon' => 'users', 'url' => ['/grup/index']],
            ['label' => 'Jabatan', 'icon' => 'star', 'url' => ['/jabatan/index']],
            ['label' => 'Mutasi', 'icon' => 'refresh', 'url' => ['/pegawai-instansi/index']],
            ['label' => 'User', 'icon' => 'user', 'items' => [
                ['label' => 'Admin', 'url' => ['/user/index', 'id_user_role' => UserRole::ADMIN]],
                ['label' => 'Pegawai', 'url' => ['/user/index', 'id_user_role' => UserRole::PEGAWAI]],
                ['label' => 'Perangkat Daerah', 'url' => ['/user/index', 'id_user_role' => UserRole::INSTANSI]],
                ['label' => 'Verifikator', 'url' => ['/user/index', 'id_user_role' => UserRole::VERIFIKATOR]],
                ['label' => 'Grup', 'url' => ['/user/index', 'id_user_role' => UserRole::GRUP]],
                ['label' => 'Mapping', 'url' => ['/user/index', 'id_user_role' => UserRole::MAPPING]],
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='tukin-only') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'TUKIN', 'options' => ['class' => 'header']],
            ['label' => 'Home', 'icon' => 'circle', 'url' => ['/tukin/admin/index']],
            ['label' => 'Pegawai', 'icon' => 'users', 'url' => ['/tukin/pegawai/index']],
            ['label' => 'Rekap Tunjangan', 'icon' => 'list', 'url' => ['/tukin/pegawai/rekap']],
            ['label' => 'Jabatan', 'icon' => 'sitemap', 'url' => ['/tukin/jabatan/index']],
            ['label' => 'Instansi', 'icon' => 'building', 'url' => ['/tukin/instansi/index']],
            ['label' => 'Instansi Kordinatif', 'icon' => 'building-o', 'url' => ['/tukin/instansi-kordinatif/index']],
            ['label' => 'Serapan Anggaran', 'icon' => 'building', 'url' => ['/tukin/instansi-serapan-anggaran/index']],
            ['label' => 'Referensi', 'icon' => 'list', 'items' => [
                ['label' => 'Variabel Objektif', 'icon' => 'circle', 'url' => ['/tukin/ref-variabel-objektif/index']]
            ]],
            ['label' => 'Ganti Password', 'icon' => 'lock', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='dev') {

    echo $this->render('//layouts/_menu-admin-dev');

}
