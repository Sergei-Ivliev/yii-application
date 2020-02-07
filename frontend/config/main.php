<?php

use frontend\modules\api\Module;
use yii\rest\UrlRule;
use yii\web\JsonParser;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$config = [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['api', 'log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
//        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
//        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => JsonParser::class,
                'charset' => 'UTF-8'
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'controller' => 'api/task',
                    'class' => UrlRule::class,
                    // откл трансформацию task в tasks
                     'pluralize' => true,
                    'extraPatterns' => [
                        // 'METHOD action' =>'actionFunction,
                        'POST random/<count>' => 'random',
                    ],
                ],
                [
                    'controller' => 'v1/task',
                    'class' => UrlRule::class,
                    //отключим трансформацию task в tasks
                     'pluralize' => true,
                    'extraPatterns' => [
                        //'METHOD action' => 'actionFunction',
                        'POST random/<count>' => 'random',
                    ],
                ],
                [
                    'class' => UrlRule::class,
                    'controller' => 'api/user',
                    'pluralize' => true,
                    'extraPatterns' => [
                        // actions
                        'GET me' => 'me',
                        'GET <id>/tasks' => 'tasks',
                    ],
                ],
//                'view'=>[
//                    'theme' => [
//                        'basePath' => '@app/themes/first',  //базовая директория со стилизованными ресурсами (CSS, JS, изображения)
//                        'baseUrl' => '@web/themes/first',   // базовый адрес доступа к стилизованным ресурсам.
//                        'pathMap' => [  //правила замены файлов view
//                            '@app/views/user' => '@app/themes/first/user',
//                            '@app/modules' => '@app/themes/first/modules',
//                            '@app/widgets' => '@app/themes/first/widgets',
//                        ],
//                    ]
//                ],

//                '' => 'site/index',
//                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
    ],
    'modules' => [
        'api' => [
            'class' => Module::class
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*']
    ];
}
return $config;
