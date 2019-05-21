<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.2.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

namespace EdSDK\FileUploaderServer\lib\action;

abstract class AAction {

    protected $m_config;

    public function setConfig($config) { $this->m_config = $config; }

	public abstract function getName();
	public abstract function run($req);

	protected function validateBoolean($b, $defaultValue) { return $b === null ? $defaultValue : $b; }
	protected function validateInteger($i, $defaultValue) { return $i === null ? $defaultValue : $i; }
	protected function validateString($s, $defaultValue) { return $s === null ? $defaultValue : $s; }

}