<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Instansi;
use app\models\Pegawai;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class InstansiController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $this->stdout("Hello World");
    }

    public function actionJabatanKepalaTidakSamaDenganSatu()
    {
        $query = Instansi::find();

        foreach($query->all() as $instansi) {
            $jumlah = $instansi->getManyJabatan()->andWhere(['status_kepala'=>1])->count();
            if($jumlah!=1) {
                $this->stdout("ID : $instansi->id; Nama : $instansi->nama".PHP_EOL);
            }
        }
    }
}
