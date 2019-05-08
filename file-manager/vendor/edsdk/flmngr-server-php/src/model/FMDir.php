<?php
/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.2.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

namespace EdSDK\FlmngrServer\model;

class FMDir {

    private $path; // contains parent dir's path WITHOUT starting AND trailing "/"
    private $name;

    public $f;
    public $d;
    public $p; // property exists in PHP version only, for JSON generation

    function __construct($name, $path, $filesCount, $dirsCount) {
        $this->path = $path;
        $this->name = $name;
        $this->f = $filesCount;
        $this->d = $dirsCount;
        $this->p = (strlen($this->path) > 0 ? ("/" . $this->path) : "") . "/" . $this->name;
    }

}
