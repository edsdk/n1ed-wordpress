<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

class UtilsPHP {

    public static function cleanDirectory($dir) {
        UtilsPHP::delete($dir, false);
    }

    public static function delete($dirOrFile, $deleteSelfDir = true) {
        if (is_file($dirOrFile)) {
            if (!unlink($dirOrFile))
                throw new Exception('Unable to delete file `' . $dirOrFile . '``');
        }
        elseif (is_dir($dirOrFile)) {
            $scan = glob(rtrim($dirOrFile,'/').'/*');
            foreach($scan as $index=>$path) {
                UtilsPHP::delete($path);
            }
            if ($deleteSelfDir)
                if (!rmdir($dirOrFile))
                    throw new Exception('Unable to delete directory `' . $dirOrFile . '``');
        }
    }

    public static function copyFile($src, $dst) {
        if (!copy($src, $dst))
            throw new Exception('Unable to copy file `' . $src . '` to `' . $dst . '`');
    }

    public static function normalizeNoEndSeparator($path) {
        // TODO: normalize
        return rtrim($path,'/');
    }

}