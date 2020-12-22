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

        // add_action('wp_enqueue_scripts', [$this, 'edsdk_enqueue_editor']);

        add_action(
            'admin_enqueue_scripts',
            [$this, 'edsdk_enqueue_editor'],
            10,
            1
        );

        add_action('rest_api_init', function () {
            register_rest_route('edsdk-n1ed/v1', '/flmngr', [
                'methods' => 'POST',
                'callback' => [$this, 'flmngrProcess'],
                'permission_callback' => function () {
                    return is_admin();
                },
            ]);
        });

        add_action('rest_api_init', function () {
            register_rest_route('edsdk-n1ed/v1', '/saveApi', [
                'methods' => 'POST',
                'callback' => [$this, 'saveApi'],
                'permission_callback' => function () {
                    return is_admin();
                },
            ]);
        });

        // add_action('admin_print_footer_scripts', [$this, 'remove_tinymce']);
    }

    public function tiny_mce_plugins()
    {
        return [];
    }

    public function edsdk_enqueue_editor($hook)
    {
        global $post;

        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if (has_action('admin_print_footer_scripts', 'wp_tiny_mce')) {
                // remove_action('admin_print_footer_scripts', 'wp_tiny_mce', 25);
            }
            wp_enqueue_script(
                'n1ed',
                $this->plugin_url . '/js/n1edLoader.js',
                [],
                false,
                true
            );
        }
    }

    public function remove_tinymce()
    {
        // if (has_action('admin_print_footer_scripts', 'wp_tiny_mce')) {
        //     remove_action('admin_print_footer_scripts', 'wp_tiny_mce', 25);
        // }
        // $this->enqueue_n1ed_js();
    }

    // add_action('wp_enqueue_scripts', [$this, 'enqueue_n1ed_js']);

    public function enqueue_editor()
    {
    }

    public function flmngrProcess(WP_REST_Request $request)
    {
        // is_admin();
    }

    public function saveApi(WP_REST_Request $request)
    {
        update_option('n1edApiKey', $_REQUEST['n1edApiKey']);
        update_option('n1edToken', $_REQUEST['n1edToken']);
        return true;
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
