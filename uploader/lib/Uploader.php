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

class Uploader {

    protected $m_actions;
    protected $m_config;

    public function __construct($config, $actions) {
        $this->m_config = $config;
        $this->m_actions = $actions;
    }

    public function run($req) {
        $actionName = $req->action;
        $action = $this->m_actions->getAction($actionName);
		if ($action === null) {
            $action  = $this->m_actions->getActionError();
            $req = ReqError::createReqError(Message::createMessage(Message::ACTION_NOT_FOUND));
        }
		$action->setConfig($this->m_config);
		$resp = null;
		try {
            $resp = $action->run($req);
        } catch (MessageException $e) {
            $resp = new RespFail($e->getFailMessage());
        }
		return $resp;
	}

}