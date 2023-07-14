<?php

$params = require __DIR__ . '/params.php';
if (file_exists(__DIR__ . '/params_local.php')) {
    $paramsLocal = require __DIR__ . '/params_local.php';
    $params = array_merge($params, $paramsLocal);
}
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'timeZone' => 'Asia/Jakarta',
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'as log' => \yii\queue\LogBehavior::class,
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex that used to sync queries
        ],
        //'db' => require(__DIR__ . '/db.php'),
        'db_kinerja' => require __DIR__ . '/db_kinerja.php',
        'db_absensi' => require __DIR__ . '/db_absensi.php',
        'db_iclock' => require __DIR__ . '/db_iclock.php',
        'db_tandatangan' => require __DIR__ . '/db_tandatangan.php',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                // ...
                'app\migrations',
                'yii\queue\db\migrations',
            ],
        ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
