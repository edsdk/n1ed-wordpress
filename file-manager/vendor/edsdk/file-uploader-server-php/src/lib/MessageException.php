<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.2.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

namespace EdSDK\FileUploaderServer\lib;

use Exception;

class MessageException extends Exception {

    protected $m_message;

    public function __construct($message) {
        parent::__construct();
        $this->m_message = (array)$message;
    }

    public function getFailMessage() {
        return $this->m_message;
    }

}
