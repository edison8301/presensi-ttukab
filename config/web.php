<?php

use app\models\User;

$params = require(__DIR__ . '/params.php');
if (file_exists(__DIR__ . '/params_local.php')) {
    $paramsLocal = require(__DIR__ . '/params_local.php');
    $params = array_merge($params, $paramsLocal);
}

$config = [
    'id' => 'DES',
    'name' => 'e-Tukin',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'language' => 'id_ID',
    'timeZone' => 'Asia/Jakarta',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
    'on beforeRequest' => function () {
        /*
        if (!Yii::$app->user->isGuest && User::isMapping()) {
            Yii::$app->session->destroy();
            Yii::$app->response->redirect(['site/login']);
            Yii::$app->end();
        }
        */
    },
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/adminlte'
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Kt9LqQUDkRoWtXO2o-FeWUkPGYIMcw6-',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'qr' => [
            'class' => '\Da\QrCode\Component\QrCodeComponent',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'identityCookie' => [
                'name' => '_tukinUser',
                'path' => '/web',
            ],
            /*'authTimeout' => $params['authTimeout'],*/
            'on beforeLogin' => function ($event) {
                if ($event->identity->force_logout && $event->cookieBased) {
                    $event->isValid = false;
                }
            },
            'on afterLogin' => function ($event) {
                if ($event->identity->force_logout) {
                    $event->identity->force_logout = false;
                    $event->identity->save();
                }
            }
        ],
        'session' => [
            'name' => '_tukinUser',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        //'db_adms' => require(__DIR__ . '/db_adms.php'),
        //'db_kinerja' => require(__DIR__ . '/db_kinerja.php'),
        //'db_absensi' => require(__DIR__ . '/db_absensi.php'),
        'db_iclock' => require(__DIR__ . '/db_iclock.php'),
        //'db_tandatangan' => require(__DIR__ . '/db_tandatangan.php'),
        //'db_sakip' => require(__DIR__ . '/db_sakip.php'),
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'as log' => \yii\queue\LogBehavior::class,
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => \yii\mutex\MysqlMutex::class, // Mutex that used to sync queries,
            'ttr' => 20 * 60,
        ],

        /*'urlManager' => [
            'enablePrettyUrl' => false,
            //'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
            ],
        ],*/

        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key'   => 'secrets',
        ],
    ],
    'modules' => [
        'kinerja' => [
            'class' => 'app\modules\kinerja\Module'
        ],
        'absensi' => [
            'class' => 'app\modules\absensi\Module'
        ],
        'tunjangan' => [
            'class' => 'app\modules\tunjangan\Module'
        ],
        'iclock' => [
            'class' => 'app\modules\iclock\Module'
        ],
        'tukin' => [
            'class' => 'app\modules\tukin\Module'
        ],
        'tandatangan' => [
            'class' => 'app\modules\tandatangan\Module',
        ],
        'remote' => [
            'class' => 'app\modules\remote\Module'
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
        'api2' => [
            'class' => 'app\modules\api2\Module',
        ],
        'apidaspeg' => [
            'class' => 'app\modules\apidaspeg\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'traceLine' => '<a href="subl://open?url={file}&line={line}">{file}:{line}</a>',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'Adminlte' => '@app/themes/adminlte/gii/crud',
                ]
            ],
        ],
    ];
    $config['modules']['utility'] = [
        'class' => 'c006\utility\migration\Module',
    ];
}

return $config;
