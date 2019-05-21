<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.3.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

/** Exit if accessed directly. */
if ( ( ! defined( 'ABSPATH' ) ) && ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

/** Remove site options. */
delete_option( 'n1ed_key' );
delete_option( 'n1ed_dev_prefix' );

/** Remove site options in Multisite. */
delete_site_option( 'n1ed_key' );
delete_site_option( 'n1ed_dev_prefix' );