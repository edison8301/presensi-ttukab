<?php

namespace app\commands;

use yii\console\Controller;
use Yii;
use yii\helpers\Console;
use app\modules\iclock\models\Checkinout;

/**
 * WIP
 */
class PresensiController extends Controller
{
    public function actionFilter($pin, $SN)
    {
        $query = Checkinout::find()
            ->joinWith(['userinfo'])
            ->andWhere(['userinfo.badgenumber' => $pin]);
        /*if (is_array($SN)) {
            $query->andWhere(['not in', 'checkinout.SN', $SN]);
        } else {
            $query->andWhere(['!=', 'checkinout.SN', $SN]);
        }*/
        //$query->groupBy('checkinout.SN');
        echo $query->createCommand()->getRawSql();die;
        $i = 0;
        $this->stdout("Deleting unnecessary presence data..");
        foreach ($query->all() as $checkinout) {
            $checkinout->delete();
            $this->stdout(".");
            $i++;
        }
        $this->stdout("Done! $i presence has been deleted..\n", Console::FG_GREEN);
    }
}
