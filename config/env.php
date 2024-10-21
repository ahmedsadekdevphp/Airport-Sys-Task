<?php

return [
    'DB_HOST' => 'localhost:3306',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'DB_NAME' => 'airport_management_sys',
    'APPROOT' => dirname(dirname(__FILE__)),
    'URLROOT' => 'http://localhost/airport-management',
    'SITENAME' => 'Airport Management',
    'DEFAULT_LANGUAGE' => 'en',
    'PAGINATE_NUM' => '10',
    'FIRST_PAGE' => '1',
    'USER_STATUS_APPROVED' => '1',
    'USER_STATUS_DISABLED' => '0',
    'APP_SECRET_KEY' => 'TYJHfksp49fmr948nmfmsddfskdsflp498mdslff',
    'RATE_LIMIT' => 5,
    'TIME_FRAME_IN_SECONDS' => 60,
    'throttle' => [
        'create' => [
            'count' => 10,
            'time_frame' => 30
        ],
        'update' => [
            'count' => 5,
            'time_frame' => 60
        ],
        'delete' => [
            'count' => 3,
            'time_frame' => 60
        ]
    ],
    'MAIL_HOST' => 'salvalenti.com',
    'MAIL_USERNAME' => 'test@salvalenti.com',
    'MAIL_PASSWORD' => ']laUi#.bnx}F',
    'MAIL_PORT' => 465, 
    'MAIL_ENCRYPTION' => 'ssl', 
    'ADMIN_ROLE'=>'admin',
    'REDIS_HOST'=>'redis-10197.c8.us-east-1-2.ec2.redns.redis-cloud.com',
    'REDIS_PORT'=>'10197',
    'REDIS_PASSWORD'=>'1yqW3a6khbglWHydI3MzQ1Uq9h89OAfn'
];
