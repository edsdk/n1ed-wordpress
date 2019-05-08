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

class FMFile {

    public $p; // contains parent dir's path WITHOUT starting AND trailing "/"

    public $s;
    public $t;
    public $w;
    public $h;

    function __construct($path, $name, $size, $timeModification, $imageInfo) {
        $this->p = "/" . $path . "/" . $name;
        $this->s = $size;
        $this->t = $timeModification;
        $this->w = $imageInfo->width == 0 ? null : $imageInfo->width;
        $this->h = $imageInfo->height == 0 ? null : $imageInfo->height;
    }

    public function getFullPath() { return ; }

}
