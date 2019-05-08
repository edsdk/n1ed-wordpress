<?php

require __DIR__ . '/vendor/autoload.php';

use EdSDK\FlmngrServer\FlmngrServer;

FlmngrServer::flmngrRequest(
    array(
        'dirFiles' => '../../../uploads/n1ed',
        'dirTmp'   => '../../../uploads/n1ed_tmp',
        'dirCache' => '../../../uploads/n1ed_cache'
    )
);