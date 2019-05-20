<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.2.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

namespace EdSDK\FlmngrServer\fs;

interface IFMDiskFileSystem {

    function getImagePreview($filePath, $width, $height);
    function getImageOriginal($filePath);
    function getDirs();
    function deleteDir($dirPath);
    function createDir($dirPath, $name);
    function renameFile($filePath, $newName);
    function renameDir($dirPath, $newName);
    function getFiles($dirPath); // with "/root_dir_name" in the start
    function deleteFiles($filesPaths);
    function copyFiles($filesPaths, $newPath);
    function moveFiles($filesPaths, $newPath);
    function moveDir($dirPath, $newPath);
    function copyDir($dirPath, $newPath);
    function getDirZipArchive($dirPath, $out);

}
