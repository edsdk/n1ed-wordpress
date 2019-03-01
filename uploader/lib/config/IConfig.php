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

interface IConfig {

    public function setTestConfig($testConf);

    public function getBaseDir();
    public function getTmpDir();

    public function getMaxUploadFileSize();
    public function getAllowedExtensions();
    public function getJpegQuality();

    public function getMaxImageResizeWidth();
    public function getMaxImageResizeHeight();

    public function getCrossDomainUrl();

    public function doKeepUploads();

    public function isTestAllowed();

    public function getRelocateFromHosts();

}