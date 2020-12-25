<?php
/*
Plugin Name: N1ED WYSIWYG editor
Plugin URI: https://n1ed.com
Description: Build modern HTML pages with editor based on CKEditor or TinyMCE
Version: 0.0.1
Requires at least: 5.6
Tested up to: 5.6
Requires PHP: 5.6
Author: Dmitriy Komarov, Ilia Kandaurov
Author URI: https://n1ed.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: edsdk-n1ed


Create stunning landing pages, design news and media sites with easy!

Widgets • Bootstrap grid • gallery of templates • custom blocks & templates
File Manager • Image Editor • and other features

*/

/**
 * Main plugin file.
 * @package edsdk-n1ed
 */

require __DIR__ . '/vendor/autoload.php';

use EdSDK\FlmngrServer\FlmngrServer;
class N1ED
{
    private $plugin_url;

    public function __construct()
    {
        add_filter('use_block_editor_for_post_type', '__return_false', 1000);
        $this->plugin_url = plugins_url('', __FILE__);
        add_filter('tiny_mce_plugins', [$this, 'tiny_mce_plugins'], 999);
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_filter('plugin_action_links', [$this, 'add_settings_link'], 10, 2);

        add_action(
            'admin_enqueue_scripts',
            [$this, 'edsdk_enqueue_editor'],
            10,
            1
        );

        add_action('rest_api_init', function () {
            register_rest_route('edsdk-n1ed/v1', '/flmngr', [
                'methods' => 'POST, GET',
                'callback' => [$this, 'flmngrProcess'],
                'permission_callback' => function () {
                    return wp_validate_auth_cookie('', 'logged_in');
                },
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('edsdk-n1ed/v1', '/get', [
                'methods' => 'GET',
                'callback' => [$this, 'getApi'],
                'permission_callback' => function () {
                    return wp_validate_auth_cookie('', 'logged_in');
                },
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('edsdk-n1ed/v1', '/logout', [
                'methods' => 'GET',
                'callback' => [$this, 'logout'],
                'permission_callback' => function () {
                    return wp_validate_auth_cookie('', 'logged_in');
                },
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('edsdk-n1ed/v1', '/saveApi', [
                'methods' => 'POST',
                'callback' => [$this, 'saveApi'],
                'permission_callback' => function () {
                    return wp_validate_auth_cookie('', 'logged_in');
                },
            ]);
        });
    }

    public function logout(WP_REST_Request $request)
    {
        update_option('n1edApiKey', 'N1EDDFLT');
        update_option('n1edToken', '');
        return true;
    }

    public function tiny_mce_plugins()
    {
        return [];
    }

    public function edsdk_enqueue_editor($hook)
    {
        global $post;

        if ($hook == 'post-new.php' || $hook == 'post.php') {
            wp_enqueue_script(
                'n1ed',
                $this->plugin_url . '/js/n1edLoader.js',
                [],
                false,
                true
            );

            $apiKey = get_option('n1edApiKey');
            if (!$apiKey) {
                $apiKey = 'N1EDDFLT';
                update_option('n1edApiKey', $apiKey);
            }
            wp_localize_script('n1ed', 'n1ed_ajax_object', [
                'apiKey' => $apiKey,
                'token' => get_option('n1edToken'),
                'urlFiles' => wp_upload_dir()['baseurl'],
            ]);
        }
    }

    public function flmngrProcess(WP_REST_Request $request)
    {
        $files = wp_upload_dir()['basedir'];
        $tmp = get_temp_dir();
        $cache = plugin_dir_path(__FILE__) . '/flmngr-cache/';

        FlmngrServer::flmngrRequest([
            'dirFiles' => $files,
            'dirTmp' => $tmp,
            'dirCache' => $cache,
        ]);
    }

    public function saveApi(WP_REST_Request $request)
    {
        update_option('n1edApiKey', $_REQUEST['n1edApiKey']);
        update_option('n1edToken', $_REQUEST['n1edToken']);
        return true;
    }

    public function getApi(WP_REST_Request $request)
    {
        $apiKey = get_option('n1edApiKey');
        if (!$apiKey) {
            $apiKey = 'N1EDDFLT';
            update_option('n1edApiKey', $apiKey);
        }

        return json_encode([
            'apiKey' => $apiKey,
            'token' => get_option('n1edToken'),
        ]);
    }

    public function add_settings_page()
    {
        add_options_page(
            'N1ED Settings',
            'N1ED',
            'manage_options',
            'edsdk-n1ed',
            [$this, 'render_plugin_settings_page']
        );
    }

    public function render_plugin_settings_page()
    {
        if (!defined('N1ED_SETTINGS')) {
            define('N1ED_SETTINGS', true);
        }

        include_once plugin_dir_path(__FILE__) . 'n1ed_settings.php';
    }

    public function add_settings_link($links, $file)
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=edsdk-n1ed'),
            __('Settings', 'edsdk-n1ed')
        );
        $links = (array) $links;
        $links['tma_settings_link'] = $settings_link;

        return $links;
    }
}

new N1ED();
