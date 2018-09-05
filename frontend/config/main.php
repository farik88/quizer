<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name'=> 'Quizer',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'layout' => 'main-quizer',
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
    ],
    'components' => [
        'view' => [
            'params' => [
                'logo' => '@web/images/logo.png'
            ]
        ],
        'request' => [
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
        ],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true,
            'cache' => false,
            'cacheExpire' => 0,
            'services' => [
                'google' => [
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'title' => 'Google',
                    // register your app here: https://code.google.com/apis/console/
                    'clientId' => 'CHANGE IT IN main-lockal.php',
                    'clientSecret' => 'CHANGE IT IN main-lockal.php',
                ],
                /* Facebook auth was temporarily disabled
                'facebook' => [
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    // register your app here: https://developers.facebook.com/apps/
                    'clientId' => 'CHANGE IT IN main-lockal.php',
                    'clientSecret' => 'CHANGE IT IN main-lockal.php',
                ],*/
            ]
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6Len1EAUAAAAACisqjzoXkwsBVPLDKEqWrwr10-8',
            'secret' => '6Len1EAUAAAAAPQNhlvevqQm9Rx8umhktYgxbmfN',
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
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'login/<service:google|facebook>' => 'site/login',
                '<action:login|logout|signup|contact|about|history>' => 'site/<action>',
                'question/create' => 'quizes/create-quize',
                'question/success' => 'quizes/create-success',
            ],
        ],
    ],
    'params' => $params,
];
