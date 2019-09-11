<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.2.0
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

if ( ! class_exists( 'n1ed_class' ) ) :
    
    class n1ed_class {

        private $settings_page_name = 'n1ed_settings';
        private $settings_api_key_field = 'n1ed_key';

        const CONFLICT_EDITORS = array(
            'ckeditor-for-wordpress/ckeditor_wordpress.php',
            'gutenberg/gutenberg.php',
            'markdown-editor/markdown-editor.php'
        );

        // Init N1ED
        function __construct($n1ed_key) {

            // Add settings page and link

            add_action('admin_menu', array($this, 'settings_page_add'));
            add_action('admin_init', array($this, 'settings_page'));

            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'settings_page_add_link'));

            /** Use minified libraries if SCRIPT_DEBUG is turned off. */
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
            
            /** Add N1ED styles. */
            wp_enqueue_style('n1ed_plugin', plugin_dir_url(__FILE__) . '/css/n1ed' . $suffix . '.css');
            wp_enqueue_script('n1ed_plugin', plugin_dir_url(__FILE__) . '/js/n1ed' . $suffix . '.js');

            if ( ! $n1ed_key ) {
                
                add_action('admin_notices', function () {
                    ?>
                    <div class="notice notice-warning">
                        <p><?php _e('Enter N1ED API key on <a href="' . esc_url(admin_url('/options-general.php?page=' . $this->settings_page_name)) . '">settings page</a>.') ?></p>
                    </div>
                    <?php
                });
            }
            
            /** Add N1ED scripts. */
            $cdn_url = 'https://cdn.n1ed.com/cdn/';
            
            $dev_prefix = get_option( 'n1ed_dev_prefix' );
            if ( $dev_prefix ) {
                $cdn_url = 'https://' . $dev_prefix . '.cdn.n1ed.com/cdn/';
            }
            
            wp_register_script( 'n1ed_core', $cdn_url . $n1ed_key . '/n1ed.js?cms=wp', array(), null, true );
            wp_enqueue_script( 'n1ed_core' );
        }

        // Add settings link to plugins page
        public function settings_page_add_link($links) {

            $links = array_merge(array(
                '<a href="' . esc_url(admin_url('/options-general.php?page=' . $this->settings_page_name)) . '">' . __('Settings') . '</a>'
                    ), $links);

            return $links;
        }

        public function settings_page_add() {
            add_options_page(__('N1ED Settings', 'n1ed'), __('N1ED', 'n1ed'), 'manage_options', $this->settings_page_name, array($this, 'settings_page_output'));
        }

        // Output settings page
        public function settings_page_output() {
            ?>
            <div class="wrap">
                <h2><?php echo get_admin_page_title() ?></h2>
                <form method="post" enctype="multipart/form-data" action="options.php">
                    <?php
                    settings_fields($this->settings_page_name);
                    do_settings_sections($this->settings_page_name);
                    submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

        // Fields for settings page

        public function settings_page() {

            global $pagenow;

            if (
                ($pagenow == 'options-general.php') &&
                (!isset($_REQUEST['settings-updated'])) &&
                (isset($_GET['page'])) &&
                ($_GET['page'] == 'n1ed_settings')
            ) {

                add_action('admin_notices', function () {

                    $api_key_valid_message = $this->sanitize_n1ed_api_key_helper(get_option($this->settings_api_key_field));

                    if ($api_key_valid_message != '') {
                        ?>
                        <div class="notice notice-error">
                            <p><?php echo $api_key_valid_message; ?></p>
                        </div>
                        <?php
                    }
                });
            }

            add_settings_section('main_settings', '', '', $this->settings_page_name);

            add_settings_field( $this->settings_api_key_field, 'API key <span class="required">*</span>', array( $this, 'fill_n1ed_key' ), $this->settings_page_name, 'main_settings' );
            register_setting( $this->settings_page_name, $this->settings_api_key_field, array( $this, 'sanitize_n1ed_key' ) );

            /** Render Domain Prefix only for developers. */
            if ( isset( $_GET['dev'] ) || get_option( 'n1ed_dev_prefix' ) ){
                add_settings_field( 'n1ed_dev_prefix', 'Domain Prefix', array( $this, 'render_dev_prefix' ), $this->settings_page_name, 'main_settings' );
            }            
            register_setting( $this->settings_page_name, 'n1ed_dev_prefix' );
        }
        
        /**
         * Render Domain Prefix field.
         *
         * @since 1.0.0
         * @access public
         **/
        public function render_dev_prefix() {
            ?>
                <input type="text" name="n1ed_dev_prefix" value="<?php echo esc_attr( get_option( 'n1ed_dev_prefix' ) ); ?>"/>
                <p>
                    By default scripts loaded from 'https://cdn.n1ed.com/...'<br/>
                    Enter 'pre' in the field to load from 'https://pre.cdn.n1ed.com/...'<br/>
                    You can specify any prefix.
                </p>
            <?php
        }

        /** Fill API key field. */
        public function fill_n1ed_key() {
            print '
                <input type="text" required="true" name="' . $this->settings_api_key_field . '" value="' . esc_attr(get_option($this->settings_api_key_field)) . '"/>
                <p>
                    Please specify API key of your N1ED configuration.<br/>
                    The list of your configuration is located into dashboard on <a href="https://n1ed.com/dashboard" target="_blank">n1ed.com</a>.<br/>
                    You can use N1ED without an API key on localhost only.<br/>
                    <a href="https://n1ed.com/dashboard" target="_blank" class="button" style="margin-top: 5px">N1ED Dashboard</a>
                </p>
            ';
        }

        // Sanitize API key field
        public function sanitize_n1ed_key( $input ) {

            $current_option = get_option('n1ed_key');

            // Check empty
            if ( $input == '' ) {

                add_settings_error( $this->settings_api_key_field, 'empty', __( 'API key is requried', 'n1ed'), 'error' );

                return $current_option;
            }

            $api_key_valid_message = $this->sanitize_n1ed_api_key_helper( $input );

            if ( $api_key_valid_message != '' ) {

                add_settings_error( $this->settings_api_key_field, 'api_key', __( $api_key_valid_message ), 'error' );

                return $current_option;
            }

            return $input;
        }

        // Helper for API key validate
        private function sanitize_n1ed_api_key_helper( $api_key ) {

            $api_key_valid_message = '';

            if ( ( isset( $api_key ) ) && ( $api_key != '' ) ) {

                $args = http_build_query(
                    array(
                        'apiKey' => $api_key
                    )
                );

                $context = stream_context_create(
                    array( 'http' =>
                        array(
                            'method' => 'POST',
                            'header' =>
                            "Content-Type: application/x-www-form-urlencoded\r\n",
                            'content' => $args
                        )
                    )
                );

                $result = file_get_contents( 'https://o.n1ed.com/conf/check', false, $context );

                $status_line = $http_response_header[0];

                preg_match( '{HTTP\/\S*\s(\d{3})}', $status_line, $match );

                $status = $match[1];

                if ( $status !== "200" ) {
                    $api_key_valid_message = 'Check API key error. Response status: ' . $status;
                }

                if ( $result ) {

                    $result = json_decode( $result );

                    if ( isset( $result->data ) && $result->data == false ) {
                        $api_key_valid_message = 'N1ED configuration not found. Check it on <a href="https://n1ed.com/dashboard" target="_blank">https://n1ed.com/</a>';
                    }
                }
            } else {
                $api_key_valid_message = 'Please specify N1ED API key in order to edit your contents. Obtain it on <a href="https://n1ed.com/dashboard" target="_blank">https://n1ed.com/</a>';
            }

            if ( $api_key_valid_message ) {
                $api_key_valid_message = '<div class="alert alert-warning" role="alert">' . $api_key_valid_message . '</div>';
            }

            return $api_key_valid_message;
        }

        // Prepare directories
        public static function prepare_directories() {

            if (!is_dir(ABSPATH . 'wp-content/uploads/n1ed')) {
                mkdir(ABSPATH . 'wp-content/uploads/n1ed');
            }

            if (!is_dir(ABSPATH . 'wp-content/uploads/n1ed/uploads')) {
                mkdir(ABSPATH . 'wp-content/uploads/n1ed/uploads');
            }

            if (!is_dir(ABSPATH . 'wp-content/uploads/n1ed_tmp')) {
                mkdir(ABSPATH . 'wp-content/uploads/n1ed_tmp');
            }

            if (!is_dir(ABSPATH . 'wp-content/uploads/n1ed_cache')) {
                mkdir(ABSPATH . 'wp-content/uploads/n1ed_cache');
            }
        }

        // Deactivate another editors
        public static function deactivate_editors() {

            // Deactivate editors
            deactivate_plugins(self::CONFLICT_EDITORS);
        }

        // Deactivate self
        public function deactivate_self() {

            // Deactivate editor
            deactivate_plugins('n1ed/n1ed.php');
        }

    } // End Class n1ed_class.
endif; // End if class_exists check.

if ( !class_exists( '_WP_Editors' ) ) {

    class _WP_Editors {

        public static function editor( $content, $editor_id, $settings = array() ) {
            print '<textarea class="n1ed-field" n1ed="true" name="' . $editor_id . '" id="' . $editor_id . '">' . $content . '</textarea>';
        }

        public static function enqueue_default_editor() {
            
        }
        
    }
}
