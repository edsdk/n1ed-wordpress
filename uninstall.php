<?php
/**
 * Plugin Name: N1ED
 * Plugin URI:  https://n1ed.com
 * Description: #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework
 * Version:     1.1.0
 * Author:      EdSDK
 * Author URI:  https://n1ed.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: n1ed
 * Domain Path: /languages
 */

/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
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
delete_option( 'n1ed_first_time_installed' );
delete_option( 'n1ed_dev_prefix' );

/** Remove site options in Multisite. */
delete_site_option( 'n1ed_key' );
delete_site_option( 'n1ed_first_time_installed' );
delete_site_option( 'n1ed_dev_prefix' );