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

class Req {

    public $action;

    public $test_clearAllFiles;
    public $test_serverConfig;

    public $m_fileName;
    public $m_fileSize;
    public $m_file;

}

class ReqError {

    public $message;

    public static function createReqError($msg) {
        $req = new ReqError();
        $req->message = $msg;
        return $req;
    }

}

class ReqUploadId extends Req {

    public $uploadId;

}

class ReqUploadAddFile extends ReqUploadId {

    public $url;

}

class ReqUploadRemoveFile extends ReqUploadId {

    public $name;

}

class ReqUploadCommit extends ReqUploadId {

    public $sizes; // of [enlarge: boolean, width: number, height: number]
    public $doCommit;
    public $autoRename;
    public $dir;
    public $files; // of [name: string, newName: string]

}