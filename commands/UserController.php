<?php

namespace app\commands;

use Yii;
use app\models\User;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Perintah konsol ini digunakan untuk membantu sinkronisasi data.
 */
class UserController extends Controller
{

    public function actionToken()
    {
        $allUser = User::find()->all();
        $count = count($allUser);
        $i = 0;
        Console::startProgress($i, $count, 'Proses :');
        foreach (User::find()->all() as $user) {
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->access_token = Yii::$app->security->generateRandomString();
            $user->save(false);
            Console::updateProgress(++$i, $count, 'Proses :');
        }
        Console::endProgress();
    }
}
