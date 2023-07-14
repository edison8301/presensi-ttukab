<?php
/**
 * Created by PhpStorm.
 * User: iqbal
 * Date: 3/6/2018
 * Time: 4:42 PM
 */

namespace app\jobs;
use yii\base\BaseObject;
use yii\helpers\Console;


class BaseJob extends BaseObject
{
    public function stdout($string)
    {
        if (Console::streamSupportsAnsiColors(\STDOUT)) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
        }

        return Console::stdout($string);
    }
}
