<?php

/* @var $this \yii\web\View */

use app\models\User;
use app\modules\kinerja\models\KegiatanStatus;
use dmstr\widgets\Menu;


if(Yii::$app->params['mode']=='tukin') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [

            ['label' => 'MENU KEHADIRAN', 'options' => ['class' => 'header']],
            ['label' => 'Rekap Kehadiran', 'icon' => 'calendar', 'url' => ['/absensi/pegawai/view']],
            /*
            ['label' => 'KINERJA PP 46/2011', 'options' => ['class' => 'header']],
            ['label' => 'Kinerja PP 46/2011','icon' => 'folder-open', 'items' => [
                ['label' => 'Rekap Kinerja', 'icon' => 'book', 'url' => ['/kinerja/pegawai/view']],
                ['label' => 'IKI', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai/index-iki']],
                ['label' => 'Daftar SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index']],
                [
                    'label' => 'Keg. Tahunan',
                    'icon' => 'address-book',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Kegiatan Utama', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                        ['label' => 'Matriks Target / Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/matriks']],
                    ]
                ],
                [
                    'label' => 'Keg. Bulanan',
                    'icon' => 'address-book',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
                        //['label' => 'Matriks', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-tahunan/matriks-bulanan']],
                        ['label' => 'Rekap Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-bulanan']],
                    ],
                ],
                [
                    'label' => 'Keg. Harian',
                    'icon' => 'address-book',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Keg. Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index']],
                        ['label' => 'Rekap Keg. Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-harian', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                        ['label' => 'Hari Ini', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-hari-ini', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                        ['label' => 'Setuju', 'icon' => 'check', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::SETUJU]],
                        ['label' => 'Konsep', 'icon' => 'pencil-square-o', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::KONSEP]],
                        ['label' => 'Periksa', 'icon' => 'search', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                        ['label' => 'Tolak', 'icon' => 'remove', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::TOLAK]],
                    ]
                ],
                [
                    'label' => 'Keg. Bawahan',
                    'icon' => 'address-book',
                    'url' => '#',
                    'items' => [
                        //['label' => 'Pegawai Bawahan', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-bawahan']],
                        ['label' => 'Keg. Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index-bawahan']],
                        ['label' => 'Keg. Bulanan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-bulanan/index-bawahan']],
                        ['label' => 'Keg. Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan']],
                        ['label' => 'Keg. Harian (Periksa)', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ]
                ],
            ]],
            ['label' => 'KINERJA PP 30/2019', 'options' => ['class' => 'header']],
            ['label' => 'Kinerja PP 30/2019','icon' => 'folder-open', 'items' => [
                ['label' => 'Rekap Kinerja','icon' => 'book','items' => [
                    ['label' => 'Rekap Bulanan', 'icon' => 'circle-o', 'url' => ['/kinerja/pegawai/view-v2']],
                    ['label' => 'Rekap Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/pegawai/view-tahunan']],
                ]],
                ['label' => 'Daftar SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index-v2']],
                ['label' => 'Kinerja Tahunan','icon' => 'folder','items' => [
                    ['label' => 'Kinerja Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index-v2']],
                    ['label' => 'Matriks Target / Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/matriks-v2']],
                ]],
                ['label' => 'Kinerja Bulanan', 'icon' => 'folder','items' => [
                    ['label' => 'Kinerja Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index-v2']],
                    ['label' => 'Rekap Kinerja Bulanan', 'icon' => 'list', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-bulanan-v2']],
                ]],
                [
                    'label' => 'Kinerja Harian', 'icon' => 'folder', 'items' => [
                    ['label' => 'Kinerja Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-v3']],
                    ['label' => 'Rekap Kinerja Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-harian-v3', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                    ['label' => 'Hari Ini', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-v3', 'KegiatanHarianSearch[tanggal]' => date('Y-m-d')]],
                    ['label' => 'Setuju', 'icon' => 'check', 'url' => ['/kinerja/kegiatan-harian/index-v3', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::SETUJU]],
                    ['label' => 'Konsep', 'icon' => 'pencil-square-o', 'url' => ['/kinerja/kegiatan-harian/index-v3', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::KONSEP]],
                    ['label' => 'Periksa', 'icon' => 'search', 'url' => ['/kinerja/kegiatan-harian/index-v3', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ['label' => 'Tolak', 'icon' => 'remove', 'url' => ['/kinerja/kegiatan-harian/index-v3', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::TOLAK]],
                ]
                ],
                [
                    'label' => 'Kinerja Triwulan', 'icon' => 'folder', 'items' => [
                    ['label' => 'Matriks Kinerja Triwulan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-triwulan/matriks']],
                ]
                ],
                [
                    'label' => 'Kinerja Bawahan',
                    'icon' => 'address-book',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Pegawai Bawahan', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-bawahan-v2']],
                        ['label' => 'Kinerja Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index-bawahan-v2']],
                        ['label' => 'Kinerja Bulanan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-bulanan/index-bawahan-v2']],
                        ['label' => 'Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan-v2']],
                        ['label' => 'Kinerja Harian (Periksa)', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan-v2', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ]
                ],
            ]],
            ['label' => 'PERMENPAN 6 TAHUN 2022', 'options' => ['class' => 'header', 'style' => 'color:yellow']],
            ['label' => 'Daftar SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index-v3', 'mode' => 'pegawai']],
            [
                'label' => 'Kinerja Tahunan',
                'icon' => 'folder',
                'url' => '#',
                'items' => [
                    ['label' => 'Kinerja Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-rhk/index']],
                    ['label' => 'Matriks Target/Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-rhk/matriks']],
                ]
            ],
            ['label' => 'Kinerja Bulanan', 'icon' => 'folder','items' => [
                ['label' => 'Kinerja Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index-v3']],
                ['label' => 'Rekap Kinerja Bulanan', 'icon' => 'list', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-bulanan-v3']],
            ]],
            [
                'label' => 'Kinerja Harian', 'icon' => 'folder', 'items' => [
                    ['label' => 'Kinerja Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-v4']],
                    ['label' => 'Rekap Kinerja Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-harian-v4']],
                    ['label' => 'Hari Ini', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-v4', 'KegiatanHarianSearch[tanggal]' => date('Y-m-d')]],
                    ['label' => 'Setuju', 'icon' => 'check', 'url' => ['/kinerja/kegiatan-harian/index-v4', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::SETUJU]],
                    ['label' => 'Konsep', 'icon' => 'pencil-square-o', 'url' => ['/kinerja/kegiatan-harian/index-v4', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::KONSEP]],
                    ['label' => 'Periksa', 'icon' => 'search', 'url' => ['/kinerja/kegiatan-harian/index-v4', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ['label' => 'Tolak', 'icon' => 'remove', 'url' => ['/kinerja/kegiatan-harian/index-v4', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::TOLAK]],
                ]
            ],
            [
                'label' => 'Kinerja Bawahan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Pegawai Bawahan', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-bawahan']],
                    ['label' => 'Daftar SKP', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai-skp/index-v3', 'mode' => 'atasan']],
                    ['label' => 'Kinerja Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index-bawahan-v3', 'KegiatanTahunanSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ['label' => 'Kinerja Bulanan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-bulanan/index-bawahan-v3']],
                    ['label' => 'Kinerja Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan-v3']],
                    ['label' => 'Kinerja Harian (Periksa)', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan-v3', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                ]
            ],
            ['label' => 'MENU TPP', 'options' => ['class' => 'header']],
            ['label' => 'Rekap TPP', 'icon' => 'calendar', 'url' => ['/tunjangan/pegawai/view-v3']],
            */
            ['label' => 'MENU LAIN', 'options' => ['class' => 'header']],
            ['label' => 'Tandatangan', 'icon' => 'edit', 'url' => ['/tandatangan/berkas/index','id_berkasi_status'=>3]],
            ['label' => 'Profil', 'icon' => 'user', 'url' => ['/pegawai/profil']],
            ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='absensi') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [

            ['label' => 'MENU ABSENSI', 'options' => ['class' => 'header']],
            ['label' => 'Rekap Absensi Pegawai', 'icon' => 'calendar', 'url' => ['/absensi/pegawai/view']],
            ['label' => 'MENU KINERJA', 'options' => ['class' => 'header']],
            ['label' => 'Daftar SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index']],
            [
                'label' => 'Keg. Tahunan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Kegiatan Utama', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                    ['label' => 'Matriks Target / Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/matriks']],
                ]
            ],
            [
                'label' => 'Keg. Bulanan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
                    ['label' => 'Rekap Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-bulanan']],
                ],
            ],
            [
                'label' => 'Keg. Harian',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Keg. Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index']],
                    ['label' => 'Rekap Keg. Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-harian', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                    ['label' => 'Hari Ini', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-hari-ini', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                    ['label' => 'Setuju', 'icon' => 'check', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::SETUJU]],
                    ['label' => 'Konsep', 'icon' => 'pencil-square-o', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::KONSEP]],
                    ['label' => 'Periksa', 'icon' => 'search', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ['label' => 'Tolak', 'icon' => 'remove', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::TOLAK]],
                ]
            ],
            [
                'label' => 'Keg. Bawahan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Pegawai Bawahan', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-bawahan']],
                    ['label' => 'Keg. Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index-bawahan']],
                    ['label' => 'Keg. Bulanan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-bulanan/index-bawahan']],
                    ['label' => 'Keg. Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan']],
                    ['label' => 'Keg. Harian (Periksa)', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                ]
            ],
            ['label' => 'MENU LAIN', 'options' => ['class' => 'header']],
            ['label' => 'Profil', 'icon' => 'user', 'url' => ['/pegawai/profil']],
            ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}

if(Yii::$app->params['mode']=='skp-ckhp') {

    echo Menu::widget([
        'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
        'items' => [
            ['label' => 'DASHBOARD', 'options' => ['class' => 'header']],
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => '#'],
            ['label' => 'SKP', 'options' => ['class' => 'header']],
            ['label' => 'Daftar SKP', 'icon' => 'book', 'url' => ['/kinerja/instansi-pegawai-skp/index']],
            [
                'label' => 'Keg. Tahunan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Kegiatan Utama', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index']],
                    ['label' => 'Matriks Target / Realisasi', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/matriks']],
                ]
            ],
            [
                'label' => 'Keg. Bulanan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/kegiatan-bulanan/index']],
                    ['label' => 'Rekap Keg. Bulanan', 'icon' => 'list', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-bulanan']],
                ],
            ],
            [
                'label' => 'Keg. Bawahan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Pegawai Bawahan', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-bawahan']],
                    ['label' => 'Keg. Tahunan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-tahunan/index-bawahan']],
                    ['label' => 'Keg. Bulanan', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-bulanan/index-bawahan']],
                ]
            ],
            ['label' => 'CKHP', 'options' => ['class' => 'header']],
            [
                'label' => 'Keg. Harian',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Keg. Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index']],
                    ['label' => 'Rekap Keg. Harian', 'icon' => 'clock-o', 'url' => ['/kinerja/pegawai/view-rekap-kegiatan-harian', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                    ['label' => 'Hari Ini', 'icon' => 'clock-o', 'url' => ['/kinerja/kegiatan-harian/index-hari-ini', 'KegiatanHarianSearch[id_kegiatan_status]' => null]],
                    ['label' => 'Setuju', 'icon' => 'check', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::SETUJU]],
                    ['label' => 'Konsep', 'icon' => 'pencil-square-o', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::KONSEP]],
                    ['label' => 'Periksa', 'icon' => 'search', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                    ['label' => 'Tolak', 'icon' => 'remove', 'url' => ['/kinerja/kegiatan-harian/index', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::TOLAK]],
                ]
            ],
            [
                'label' => 'Keg. Bawahan',
                'icon' => 'address-book',
                'url' => '#',
                'items' => [
                    ['label' => 'Pegawai Bawahan', 'icon' => 'circle-o', 'url' => ['/kinerja/instansi-pegawai/index-bawahan']],
                    ['label' => 'Keg. Harian', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan']],
                    ['label' => 'Keg. Harian (Periksa)', 'icon' => 'circle-o', 'url' => ['/kinerja/kegiatan-harian/index-bawahan', 'KegiatanHarianSearch[id_kegiatan_status]' => KegiatanStatus::PERIKSA]],
                ]
            ],
            ['label' => 'LAIN-LAIN', 'options' => ['class' => 'header']],
            ['label' => 'Profil', 'icon' => 'user', 'url' => ['/pegawai/profil']],
            ['label' => 'Ganti Password', 'icon' => 'key', 'url' => ['/user/change-password']],
            ['label' => 'Logout', 'url' => ['/site/logout'], 'icon' => 'power-off', 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>', 'visible' => !Yii::$app->user->isGuest],

        ],
    ]);

}
