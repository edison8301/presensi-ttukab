<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use app\modules\absensi\models\InstansiRekapAbsensi;
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
class InstansiRekapAbsensiController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionRefresh($bulan, $tahun, $offset = 0)
    {
        $query = InstansiRekapAbsensi::find();
        $query->andWhere([
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
        $allData = $query->all();

        $total = count($allData);
        $prog = 0;
        Console::startProgress($prog, $total, "Merefresh rekap absensi");
        foreach ($allData as $data) {
            $data->refresh();
            Console::updateProgress(++$prog, $total);
        }
        Console::endProgress();
    }
}
