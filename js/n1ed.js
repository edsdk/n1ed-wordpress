/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.2.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (C) 2019 N1ED (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.com
 **/

(function ($) {
    "use strict";
    
    $(document).ready(function () {

        var preview_button = $('#preview-action a');
        var content_element = $('#content');

        preview_button.click(function () {
            N1ED.updateContent(content_element.get(0));
        });
    });

})(jQuery);