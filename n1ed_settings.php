<?php
/**
 * Main plugin file.
 * @package edsdk-n1ed
 */
?>
<div id="cms-conf-placeholder"></div>
<script src='//n1ed.com/js/n1ed-cms-conf-3.js'></script>
<script>
window.attachN1EDCmsConf({
        el: document.getElementById("cms-conf-placeholder"),
        urlSetApiKeyAndToken:  '/setApiKey.php',
        apiKey: 'N1EDDFLT',
        token: '',
        editorName: 'tinymce', // or 'ckeditor' if you use it as base editor
        integration: 'wordpress', // your CMS name in lowercase, type once and do not change in future please
        isCheckBoxN1EDEcoEnabledVisible: false,
        checkBoxN1EDEcoEnabledTitle: null,
        checkBoxN1EDEcoEnabledValue: null,
        onN1EDEcoEnabledChange: function (value, onFinished) {},
        onApiKeyChange: function (apiKey, token) {}
      });
</script>
