<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;


use app\models\Schedule;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        $pegawai = new Pegawai();
        // echo Pegawai::findOne(135)->getPotonganHukumanDisiplin(2);
        /*$date = date("Y-06-t");
        $date2 = date("Y-m-01", strtotime($date . ' -3 months'));
        echo $date;*/
    }

    public function actionSchedule()
    {
        $model = new Schedule();
        $model->nama = '/hello/schedule';
        $model->waktu_buat = date('Y-m-d H:i:s');
        $model->save();
    }
}
