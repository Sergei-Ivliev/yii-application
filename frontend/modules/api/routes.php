<?php

use yii\rest\UrlRule;

return [
    'rules' => [
        [
            'class' => UrlRule::class,
            'controller' => 'api/user',
            'pluralize' => true,
            'extraPatterns' => [
                // actions
                'POST sign-up' => 'sign-up',
                'POST sign-in' => 'sign-in',
                'GET me' => 'me',
            ],
        ],
        [
            'class' => UrlRule::class,
            'controller' => 'api/task',
            'pluralize' => true,
        ],

    ],
];

