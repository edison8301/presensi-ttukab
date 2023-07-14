<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Jabatan;
use app\models\Pegawai;
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
class JabatanController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo PHP_SAPI;
        // echo Pegawai::findOne(135)->getPotonganHukumanDisiplin(2);
        /*$date = date("Y-06-t");
        $date2 = date("Y-m-01", strtotime($date . ' -3 months'));
        echo $date;*/
    }

    public function actionResetJabatanNonStrukturalKeJfu()
    {
        $query = Jabatan::find();
        $query->andWhere('id_jenis_jabatan != 1');

        $done = 0;
        $total = $query->count();
        Console::startProgress($done,$total);
        foreach($query->all() as $jabatan) {
            $jabatan->id_jenis_jabatan = 3;
            $jabatan->save();
            $done++;
            Console::updateProgress($done,$total);
        }
        Console::endProgress();
    }

    public function actionSetJabatanNonStrukturalKeJft()
    {
        $query = Jabatan::find();
        $query->andWhere('id_jenis_jabatan != 1');

        $query->andWhere("nama LIKE '%pelaksana%' OR
            nama LIKE '%penyelia%' OR
            nama LIKE '%lanjutan%' OR
            nama LIKE '%pertama%' OR
            nama LIKE '%mahir%' OR
            nama LIKE '%ahli%' OR
            nama LIKE '%terampil%' OR
            nama LIKE '%muda%' OR
            nama LIKE '%madya%' OR
            nama LIKE '%utama%'");

        $done = 0;
        $total = $query->count();

        $this->stdout("Total $total \n");

        Console::startProgress($done,$total);
        foreach($query->all() as $jabatan) {
            $jabatan->id_jenis_jabatan = 2;
            $jabatan->save();
            $done++;
            Console::updateProgress($done,$total);
        }

        Console::endProgress();
    }

    public function actionUpdateInduk()
    {
        foreach (Jabatan::find()->andWhere(['status_kepala' => true])->all() as $jabatan) {
            $this->stdout("$jabatan->nama : {$jabatan->updateAtasanKepalaSubinstansi()}\n");
        }
    }

    public function actionUpdateIdEselon()
    {
        $query = Jabatan::find();
        foreach ($query->all() as $data) {
            $data->setIdEselon();
            $data->save();
        }
    }

    public function actionUpdateNamaTahunFromNama()
    {
        $query = Jabatan::find();
        $query->andWhere('nama_2021 is null');

        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);
        foreach ($query->all() as $data) {
            $data->updateAttributes([
                'nama_2021' => $data->nama,
                'nama_2022' => $data->nama,
            ]);

            $done++;
            Console::updateProgress($done,$total);
        }

        Console::endProgress();
    }

    public function actionUpdateNama2023()
    {
        $query = Jabatan::find();
        $query->andWhere('nama_2023 is null');

        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);
        foreach ($query->all() as $data) {
            $data->updateAttributes([
                'nama_2023' => $data->nama_2022,
            ]);

            $done++;
            Console::updateProgress($done,$total);
        }

        Console::endProgress();
    }
}
