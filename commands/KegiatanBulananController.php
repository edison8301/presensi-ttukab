<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use app\models\KegiatanTahunanVersi;
use app\modules\kinerja\models\KegiatanBulanan;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class KegiatanBulananController extends Controller
{
    public function actionUpdateRealisasi($bulan=null, $tahun=null)
    {
        if($tahun == null) {
            $tahun = date('Y');
        }

        if($bulan == null) {
            $bulan = date('n');
        }

        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan']);
        $query->andWhere(['kegiatan_tahunan.tahun'=>$tahun]);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_status'=>1]);
        $query->andWhere(['kegiatan_bulanan.bulan' => $bulan]);
        $query->andWhere('kegiatan_bulanan.target IS NOT NULL');
        $query->andWhere('kegiatan_bulanan.persen_realisasi < 100');

        $total = $query->count();
        $done = 0;

        Console::startProgress($done,$total);
        foreach($query->all() as $data) {
            $data->updateRealisasi();
            $done++;
            Console::updateProgress($done, $total);
        }
    }

    public function actionUpdateTargetV1()
    {
        ini_set('memory_limit', -1);

        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan']);
        $query->andWhere(['kegiatan_tahunan.id_kegiatan_tahunan_versi' => 1]);
        $query->andWhere(['kegiatan_tahunan.tahun' => 2021]);
        $query->andWhere('kegiatan_bulanan.bulan > 6');
        $query->andWhere('kegiatan_bulanan.target is not null');

        $total = $query->count();
        $done = 0;
        Console::startProgress($done,$total);
        foreach($query->all() as $kegiatanBulanan) {
            $kegiatanBulanan->target = null;
            if(!$kegiatanBulanan->save()) {
                print_r($kegiatanBulanan->getErrors());die;
            }
            $kegiatanBulanan->updateRealisasi();
            $done++;
            Console::updateProgress($done, $total);
        }
    }

    public function actionPerawatan()
    {
        $query = KegiatanBulanan::find();
        $query->joinWith(['kegiatanTahunan']);
        $query->andWhere([
            'kegiatan_tahunan.id_kegiatan_tahunan_versi' => KegiatanTahunanVersi::PP_30_TAHUN_2O19,
            'kegiatan_tahunan.tahun' => 2023,
        ]);
        $query->andWhere('kegiatan_bulanan.bulan > 1');

        $done = 0;
        $total = $query->count();
        Console::startProgress($done,$total);
        foreach ($query->all() as $kegiatanBulanan) {
            $kegiatanBulanan->updateAttributes([
                'target' => null,
                'target_kualitas' => null,
                'target_waktu' => null,
                'target_biaya' => null,
            ]);

            Console::updateProgress($done++,$total);
        }
        Console::endProgress();
    }
}
