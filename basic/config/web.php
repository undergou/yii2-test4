<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [

        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '654408885115670',
                    'clientSecret' => 'cc2dbc84a6b1fc5e4ef967285b68811c',
                    'attributeNames' => ['name', 'email', 'first_name', 'last_name'],
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '374091443885-hupml90435llsd13i50dp6vevs4hr0t5.apps.googleusercontent.com',
                    'clientSecret' => 'HFkV9GZS6bsldBv_xWBIVufY',
                ],
            ],
        ],
        
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Wz4C17Gkvgvzk7CWBkdHELTOy3MywZ0_',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\userAuth\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'volikov.dmitrie@yandex.ru',
                'password' => '',
                'port' => '465', 
                'encryption' => 'SSL',
            ],*/
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
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //'' => 'site/index',
                'register' => 'usAuth/auth/register',
                'login' => 'usAuth/auth/login',
                'retrieve-password' => 'usAuth/default/retrieve-password',
                'admin-panel' => 'admin-panel/users/index',
            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        
    ],

    'modules' => [
        'usAuth' => [
            'class' => 'app\modules\userAuth\Module',
        ],
        'admin-panel' => [
            'class' => 'app\modules\adminPanel\Module',
        ],
    ],
    
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
