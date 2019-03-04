<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

class RespOk {

    public $ok = true;

}


class RespFail extends RespOk {

    public $message;

    public function __construct($message) {
        $this->ok = false;
        $this->message = $message;
    }

}

class RespUploadAddFile extends RespOk {

    public $file;

}

class RespUploadCommit extends RespOk {

    public $files;

}

class Settings {
    public $maxImageResizeWidth;
    public $maxImageResizeHeight;
}

class RespUploadInit extends RespOk {

    public $uploadId;
	public $settings;

	public function __construct($uploadId, $config) {
        $this->uploadId = $uploadId;
        $this->settings = new Settings();
        $this->settings->maxImageResizeWidth = $config->getMaxImageResizeWidth();
        $this->settings->maxImageResizeHeight = $config->getMaxImageResizeHeight();
    }

}
