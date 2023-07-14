<?php

namespace app\commands;

use app\models\Peta;
use yii\console\Controller;
use yii\helpers\Console;

class PetaController extends Controller
{
    public function actionDeleteDuplicatePegawaiWfh()
    {
        $query = Peta::find();
        $query->andWhere('id_pegawai is not null');
        $query->andWhere(['status_rumah' => 1]);
        $query->groupBy(['id_pegawai']);

        $done = 0;
        $total = $query->count();

        Console::startProgress($done,$total);
        foreach ($query->all() as $peta) {
            $petas = Peta::find()
                ->andWhere(['<>', 'id', $peta->id])
                ->andWhere(['id_pegawai' => $peta->id_pegawai])
                ->andWhere(['status_rumah' => 1])
                ->all();

            if (count($petas) > 0) {
                foreach ($petas as $data) {
                    $data->delete();
                }
            }

            $done++;
            Console::updateProgress($done,$total);
        }
        Console::endProgress();
    }
}
