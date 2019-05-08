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

use EdSDK\FileUploaderServer\lib\action\resp\Message;
use EdSDK\FileUploaderServer\lib\action\resp\RespUploadInit;
use EdSDK\FileUploaderServer\lib\MessageException;

class ActionUploadInit extends AAction {

    public function getName() { return "uploadInit"; }

    public function run($req){
        $alphabeth = "abcdefghijklmnopqrstuvwxyz0123456789";
        do {
            $id = "";
            for ($i=0; $i<6; $i++) {
                $charNumber = rand(0, strlen($alphabeth)-1);
                $id .= substr($alphabeth, $charNumber, 1);
            }
            $dir = $this->m_config->getTmpDir() . "/" . $id;
        } while (file_exists($dir));

        if (!mkdir($dir))
            throw new MessageException(Message::createMessage(Message::UNABLE_TO_CREATE_UPLOAD_DIR));

		return new RespUploadInit($id, $this->m_config);
	}

}