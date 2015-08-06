<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language' => 'en',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'e-commerce',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'error/home',
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
    		
    	//Url Manager
    	'urlManager' => [
    			'class' => 'yii\web\UrlManager',
    			// Disable index.php
    			'showScriptName' => false,
    			// Disable r= routes
    			'enablePrettyUrl' => true,
    			'rules' => array(
    					'<controller:\w+>/<id:\d+>' => '<controller>/view',
    					'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    					'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    					'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
    			),
    	
    	],
    	
    	//i18n Multi-language
    	'i18n' => [
        	'translations' => [
            	'app*' => [
                	'class' => 'yii\i18n\PhpMessageSource',
					'fileMap' => [
						'app' => 'app.php',
					],
           		],
        	],
    		'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation'],
    	],
    		
    ],
    'params' => $params,
	'layout' => 'frontend',
		
	//aliases is take effect from urlManage make sure to set it up correctly!
	'aliases' => [
			'@frontend' => '/ecom/web/frontend/home',
			'@backend' => '/ecom/web/backend/home',
			'@error' => '/ecom/web/error/home',
			'@maintenance' => '/ecom/web/maintenance/home',
			'@authorize' => '/ecom/web/authorize/home',
	],
		
	//Route
	'defaultRoute' => 'frontend/home',
	
// 	'catchAll' => [
//     	'maintenance/home',
// 	],
		
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
