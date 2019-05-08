<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

class ActionUploadRemoveFile extends AActionUploadId {

	public function getName() {
		return "uploadRemoveFile";
	}

	public function run($req) {
		$this->validateUploadId($req);
		$file = new FileUploaded($this->m_config, $req->uploadId, $req->name, $req->name);
		$file->checkForErrors(true);

		if ($file->getErrors()->size() > 0)
            throw new MessageException(Message::createMessageByFile(Message::UNABLE_TO_DELETE_UPLOAD_DIR, $file->getData()));

		$file->delete();
		return new RespOk();
	}

}
