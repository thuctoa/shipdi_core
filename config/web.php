<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    
    'language'=> 'vi',
    'sourceLanguage'=> 'en',
   
    //'defaultRoute'=>'book/index',
    //'catchAll'=>['book/index'],
    
    'components' => [
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zcDPFQ-c4sxL3ljnbfz3LDFIRtZ2-NBa',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            //'enableSession'=> false,
            //'loginUrl' => NULL
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
                    'levels' => ['error','warning'],
                    'logVars' => ['_POST'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['thuc'],
                    'logVars' => ['_POST'],
                ],
            ],
        ],
        
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => 
        [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix'=>'.html',
            'rules' => [
                'books' => 'book/index',
                '<controller>s' => '<controller>/index',
                '<controller>/<id:\d+>' => '<controller>/view',   
                [
                    'pattern' => 'books',
                    'route' => 'book/index',
                    'suffix' => '.html',
                ],
                'book/view/<id:\d+>'=>'book/view',
                'book/view/all'=>'book/index',
                [
                    'pattern' => 'book/<id:\d+>',
                    'route' => 'book/view',
                    'defaults' => ['id' => '2'],
                ],
                'POST book/<id:\d+>' => 'book/update',
                'GET book/<id:\d+>' => 'book/view',
                'DELETE book/<id:\d+>' => 'book/delete',
            ],
        ],
         'authManager' => [
            'class'=>'yii\rbac\DbManager',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => false,//set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'thuctoa@gmail.com',
                'password' => 'lr3fgkRh5',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
    ],
    'modules' => [
      'admin' => [
        'class' => 'mdm\admin\Module',  
      ],  
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
        ],
    ],
    'as beforeRequest' => [
        'class' => 'app\components\LanguageHandler',
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    //$config['bootstrap'][] = 'debug';
    //$config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
