<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language' => 'ru-RU',
    'defaultRoute' => 'category/index',//Route по умолчанию. До этого было site/index
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',//шаблон для админки
            'defaultRoute' => 'order/index',//Route по умолчанию для админки
        ],
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            //be sure, that permissions ok
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => 'upload/store', //здесь хранятся оригиналы картинок
            'imagesCachePath' => 'upload/cache', //здесь хранятся обрезанные картинки
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            'placeHolderPath' => '@webroot/upload/store/no-image.png', // если изображения нет, будет выводиться эта картинка
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wE5Ft-_mb7hYDTb-3mAzVmpCNzMsL1Wa',
			'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'username' => 'username',
                'password' => 'password',
                'port' => '465',
                'encryption' => 'ssl',
            ],
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
				'category/<id:\d+>/page/<page:\d+>' => 'category/view',//правило для пагинации
                'category/<id:\d+>' => 'category/view',//Правило для категорий. 'category/<id:\d+>' - как должна выглядеть ссылка, 'category/view' - чему будет соответствовать данная ссылка.
                'product/<id:\d+>' => 'product/view',//правило для карточки товара
                'search' => 'category/search',//правило для поиска
            ],
        ],
        
    ],
	'controllerMap' => [//elfinder
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],//доступ к редактору разрешён только для администратора
            'root' => [
                'baseUrl'=>'/web',
//                'basePath'=>'@webroot',
                'path' => 'upload/global',//куда будут загружаться файлы
                'name' => 'Global'//отвечает за название папки, куда будут загружаться файлы
            ],
        ]
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
