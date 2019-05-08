<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

class ActionUploadCancel extends AActionUploadId {

	public function getName() {
		return "uploadCancel";
	}

	public function run($req) {
		$this->validateUploadId($req);
		if (!$this->m_config->doKeepUploads()) {
            try {
                UtilsPHP::delete($this->m_config->getTmpDir() . "/" . $req->uploadId);
            } catch (Exception $e) {
                error_log($e);
                throw new MessageException(Message::createMessage(Message::UNABLE_TO_DELETE_UPLOAD_DIR));
            }
		}
		return new RespOk();
	}

}