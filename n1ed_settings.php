<?php
/**
 * Main plugin file.
 * @package edsdk-n1ed
 */

$apiKey = get_option('n1edApiKey');
$token = get_option('n1edToken');

if (!$apiKey) {
    add_option('n1edApiKey', 'N1WPDFLT');
    $apiKey = 'N1WPDFLT';
}
?>


<div id="cms-conf-placeholder"></div>
<script src='https://n1ed.com/js/n1ed-cms-conf-3.js'></script>
<script>
var saveFunc = window.attachN1EDCmsConf({
        el: document.getElementById("cms-conf-placeholder"),
        urlSetApiKeyAndToken:  '/wp-json/edsdk-n1ed/v1/setApiKey',
        apiKey: '<?php echo $apiKey; ?>',
        token: '<?php echo $token; ?>',
        editorName: 'tinymce', // or 'ckeditor' if you use it as base editor
        integration: 'wordpress', // your CMS name in lowercase, type once and do not change in future please
        isCheckBoxN1EDEcoEnabledVisible: false,
        checkBoxN1EDEcoEnabledTitle: null,
        checkBoxN1EDEcoEnabledValue: null,
        onN1EDEcoEnabledChange: function (value, onFinished) {},
        onApiKeyChange: function (apiKey, token) {}
      });

</script>



