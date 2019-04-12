<?php
/**
 * Plugin Name: N1ED
 * Plugin URI:  https://n1ed.com
 * Description: #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework
 * Version:     1.1.0
 * Author:      EdSDK
 * Author URI:  https://n1ed.com/resources/contacts
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
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

/** Set the activation hook for a plugin. We need it to send POST-query on activation. */
register_activation_hook( __FILE__, array( 'N1EDCore', 'on_activation' ) );

if ( ! class_exists( 'N1EDCore' ) ) :
    
    /**
     * SINGLETON: Core class used to implement a N1EDCore plugin.
     *
     * This is used to define internationalization, admin-specific hooks, 
     * and public-facing site hooks.
     *
     * @since 1.0.0
     **/
    final class N1EDCore {
    
        /** 
         * Plugin version.
         *
         * @var Constant
         * @since 1.0.0
         **/
        const VERSION = '1.0.0';
        
        /**
         * The one true N1EDCore.
         * 
	 * @var N1EDCore
	 * @since 1.0.0
	 **/
	private static $instance;
        
        /**
         * Main N1EDCore Instance.
         *
         * Insures that only one instance of N1EDCore exists in memory at any one time.
         *
         * @static
         * @return N1EDCore
         * @since 1.0.0
         **/
        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof N1EDCore ) ) {
                self::$instance = new N1EDCore;
            }

            return self::$instance;
        }

        /**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 **/
	public function __clone() {
            /** Cloning instances of the class is forbidden. */
            _doing_it_wrong( __FUNCTION__, __( 'The whole idea of the singleton design pattern is that there is a single object therefore, we don\'t want the object to be cloned.', 'n1ed' ), self::VERSION );
	}

        /**
	 * Disable unserializing of the class.
         * 
         * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be unserialized.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @return void
	 **/
	public function __wakeup() {
            /** Unserializing instances of the class is forbidden. */
            _doing_it_wrong( __FUNCTION__, __( 'The whole idea of the singleton design pattern is that there is a single object therefore, we don\'t want the object to be unserialized.', 'n1ed' ), self::VERSION );
	}
        
        /**
         * Sets up a new plugin instance.
         *
         * @since 1.0.0
         * @access public
         **/
        private function __construct() {
            /** Send POST after first activation. */
            $this->first_activation();
            
            /** Load translations. */
            add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

            /** Add plugin links. */
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_links' ) );
            
            /** 
             * Require n1ed class with plugin logic.
             * 
             * TODO: Needs refactoring, merge with N1EDCore class. 
             */
            require_once dirname(__FILE__) . '/n1ed_class.php';
            
            /** Deactivate another editors. */
            register_activation_hook( __FILE__, array( 'n1ed_class', 'deactivate_editors' ) );
            
            /** Disable Gutenberg editor for WP 5.0+. */
            add_filter('use_block_editor_for_post', '__return_false');

            /** Fix. Deactivate self after ckeditor enable. */
            $n1ed_class     = new ReflectionClass( 'n1ed_class' );
            $n1ed_constants = $n1ed_class->getConstants();

            foreach( $n1ed_constants['CONFLICT_EDITORS'] as $conflict_editor ) {
                register_activation_hook( ABSPATH . 'wp-content/plugins/' . $conflict_editor, array( 'n1ed_class', 'deactivate_self' ) );
            }

            /** Prepare directories. */
            register_activation_hook( __FILE__, array( 'n1ed_class', 'prepare_directories' ) );

            /* Init plugin. */
            add_action( 'init', array ( $this, 'n1ed_init' ) );
        }
        
        /**
         * Activation hook for a plugin. We need it to send POST-query on activation.
         *
         * @since 1.0.0
         * @access public
         **/
        public static function on_activation() {
            /** Security checks. */
            if ( ! current_user_can( 'activate_plugins' ) ){ return; }
            $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
            check_admin_referer( "activate-plugin_{$plugin}" );

            /** Add option, so that later if we have it we send POST-query. */
            add_option( 'n1ed_first_time_installed', '1' );
	}
        
        /**
         * Send POST-query on first time activation.
         *
         * @since 1.0.0
         * @access public
         **/
        public function first_activation() {
            $first_time = get_option( 'n1ed_first_time_installed' );
            
            /** If this first time activation. */
            if ( $first_time == '1') {
                
                /** Data to send by POST. */
                $data = array(
                    'api_key'           => '8ff60f6330fbe606984d4ee1e39c86e4', 
                    'event_type'        => 'cmsModuleInstalled',
                    'event_properties'  => '{cms: "wp"}'
                );
                
                $url = 'https://api.amplitude.com/httpapi';
                
                /** Form data string. */
                $postData = http_build_query( $data, '', '&' );
                
                /** Sending POST from cURL. */
                if ( extension_loaded( 'curl' ) ) {
                    /** Create a connection. */
                    $ch = curl_init( $url );

                    /** Setting our options. */
                    curl_setopt( $ch, CURLOPT_POST, TRUE );
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );

                    /** Get the response. */
                    $response = curl_exec($ch);
                    curl_close($ch);
                
                /** Sending POST from PECL. */
                } elseif ( extension_loaded( 'pecl_http' ) ) {
                    /** Get the response. */
                    $response = http_post_data( $url, $postData );
                
                /** Sending POST from PECL - OO Approach. */
                } elseif ( class_exists( 'HTTPRequest' ) ) {
                    
                    /** Create the request, state method = HTTP_METH_POST. */
                    $request = new HTTPRequest( $url, HTTP_METH_POST );
                    $request->setRawPostData( $postData );
                    
                    /** Send our request. */
                    $request->send();
                    
                    /** Get the response. */
                    $response = $request->getResponseBody();
                
                /** Sending POST using HTTP Context. */
                } elseif ( ini_get('allow_url_fopen') ) {
                    /** Form our options. */
                    $opts = array('http' =>
                        array(
                            'method'  => 'POST',
                            'header'  => 'Content-type: application/x-www-form-urlencoded',
                            'ignore_errors' => true,
                            'content' => $postData
                        )
                    );
                    
                    /** Create the context. */
                    $context = stream_context_create( $opts );
                    
                    /** Get the response (you can use this for GET). */
                    $response = file_get_contents( $url, false, $context );
                    
                /** If nothing worked, we will give a chance to WordPress. */
                } else {
                    wp_remote_post( $url, array(
                        'body'          => $data,
                        'timeout'       => 5,
                        'redirection'   => 3,
                        'blocking'      => false,
                        'httpversion'   => '1.0',
                        'method'        => 'POST',
                        'headers'       => array(),
                        'cookies'       => array()
                    ) );
                }
                                
                update_option( 'n1ed_first_time_installed', '0' );
            }
        }
        
        /**
         * Instantiates the n1ed_class class.
         *
         * @since 1.0.0
         * @access public
         **/
        public function n1ed_init() {
            new n1ed_class( get_option( 'n1ed_key' ) );
        }
        
        /**
         * Loads plugin translated strings.
         *
         * @since 1.0.0
         * @access public
         **/
        public function load_textdomain() {
            load_plugin_textdomain( 'n1ed', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
        
        /**
         * Add "Settings" and "N1ED.com" links to plugin page.
         *
         * @since 1.0.0
         * @access public
         *
         * @param array $links Current links: Deactivate | Edit
         **/
        public function add_links($links) {
            array_unshift( $links, '<a title="' . esc_attr__( 'N1ED - structured HTML content editor with CKEditor onboard.', 'n1ed' ) . '" href="https://n1ed.com/" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAzElEQVR4nGP8//8/AymAiSTVUA2xaxmc5zO8/IIioT0ZhCDgzNezSQ+S8x8VANksQHzuGUi0Zi/DbH8sRna/6Nn2cRsWJx17xLD2GhYN2pxanEyc2P3QdZjh1Vd0DZ78nosVF2HRYK/A8OUXQ8M+dA2MDIzCLMJYNDQ5M/CyMxx8wLDpBhaHYdEgwsVQZQditB1iePONCA1A4KcBctjnnwx1e4nTgOwwYjUAHVZpi1M1Fg1A4K/JYCVHigYgaHFmkOBhMJLCIsVI89QKAHcTN2OXE4tBAAAAAElFTkSuQmCC" alt="N1ED Logo" style="width: 16px; height: 16px; vertical-align: middle; position: relative; top: -2px; float: none; margin-right: 0; padding-right: 2px;"> N1ED.com</a>');
            array_unshift( $links, '<a title="' . esc_attr__( 'Settings', 'n1ed' ) . '" href="'. admin_url( 'options-general.php?page=n1ed_settings' ) .'">' . esc_attr__( 'Settings', 'n1ed' ) . '</a>');
            return $links;
        }
    } // End Class N1EDCore.
endif; // End if class_exists check.

/**
 * Instantiates the N1EDCore class.
 * N1EDCore is a singleton so we can directly access the one true N1EDCore object using this variable.
 *
 * @return object N1EDCore
 **/
$N1EDCore = N1EDCore::get_instance();
