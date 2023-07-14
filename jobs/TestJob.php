<?php

namespace app\jobs;

use Yii;


class TestJob extends BaseJob implements \yii\queue\JobInterface
{
    public $file;

    public function execute($queue)
    {
        file_put_contents(time() . ".png", file_get_contents($this->file));
    }
}
