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

class JsonCodec {

    protected $m_actions;

    public function __construct($actions) {
        $this->m_actions = $actions;
    }

    public function fromJson($json) {
        $jsonObj = json_decode($json, false);
        if ($jsonObj === null)
            throw new Exception('Unable to parse JSON');
        if (!array_key_exists('action', $jsonObj))
            throw new Exception('"Unable to detect action in JSON"');
        $action = $this->m_actions->getAction($jsonObj->action);
        if ($action === null)
            throw new Exception("JSON action is incorrect: " . $action);
        return $jsonObj;
    }

    public function toJson($resp) {
        $json = json_encode($resp);
        $json = str_replace('\\u0000*\\u0000', '', $json);
        $json = str_replace('\\u0000', '', $json);
        return $json;
    }

}