<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.3.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

require __DIR__ . '/vendor/autoload.php';

define( 'WP_USE_THEMES', false );
require( '../../../../wp-load.php' );

$upload_dir = wp_get_upload_dir();

use EdSDK\FlmngrServer\FlmngrServer;

FlmngrServer::flmngrRequest(
    array(
        'dirFiles' => $upload_dir['basedir'] . '/n1ed',
        'dirTmp'   => $upload_dir['basedir'] . '/n1ed_tmp',
        'dirCache' => $upload_dir['basedir'] . '/n1ed_cache'
    )
);
