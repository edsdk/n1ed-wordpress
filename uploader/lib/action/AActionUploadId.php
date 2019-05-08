<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

abstract class AActionUploadId extends AAction {

    protected function validateUploadId($req) {
        if ($req->uploadId === null)
            throw new MessageException(Message::createMessage(Message::UPLOAD_ID_NOT_SET));

        $dir = $this->m_config->getTmpDir() . "/" . $req->uploadId;
        if (!file_exists($dir) || !is_dir($dir))
            throw new MessageException(Message::createMessage(Message::UPLOAD_ID_INCORRECT));
    }

}
