<?php

namespace app\commands;

use yii\console\Controller;
use Yii;
use yii\helpers\Console;
use app\models\Iclock;
use yii\db\Expression;
use app\modules\iclock\models\Devcmds;

/**
 * Faker owo
 */
class FakerController extends Controller
{
    public function actionLorem()
    {
        $this->stdout(date('Y-m-d H:i:s'));
    }
    public function actionIclockStateUp()
    {
        $query = Iclock::find()
            ->where(['DelTag' => 0])
            ->orderBy(new Expression('rand()'));
        $this->stdout("Generating online devices..");
        foreach ($query->limit(random_int(20, 40))->all() as $iclock) {
            $iclock->LastActivity = date('Y-m-d H:i:s');
            $iclock->save();
            $this->stdout(".");
        }
        $this->stdout(" Done!\n", Console::FG_GREEN);
        $this->stdout("Generating communicating devices..");
        foreach ($query->limit(random_int(2, 5))->all() as $iclock) {
            $this->generateDumdumCmds($iclock);
            $iclock->LastActivity = date('Y-m-d H:i:s');
            $iclock->save();
            $this->stdout(".");
        }
        $this->stdout(" Done and done!\n", Console::FG_GREEN);
    }

    public function actionIclockStateDrop()
    {
        $query = Iclock::find()
            ->where(['DelTag' => 0]);
        foreach ($query->all() as $iclock) {
            $iclock->LastActivity = date('Y-m-d H:i:s -1 hour');
            $iclock->save();
        }
        Devcmds::deleteAll(['Cmdreturn' => null]);
        $this->stdout("Clear!", Console::FG_GREEN);
    }

    protected function generateDumdumCmds($iclock)
    {
        $cmd = new Devcmds([
            'SN_id' => $iclock->SN,
            'CmdContent' => 'DATA DEL_USER PIN=909090909090',
            'CmdCommitTime' => date('Y-m-d H:i:s'),
        ]);
        return $cmd->save();
    }
}
