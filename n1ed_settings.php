<?php
/**
 * Main plugin file.
 * @package edsdk-n1ed
 */

$apiKey = get_option('n1edApiKey');
$token = get_option('n1edToken');

if (!$apiKey) {
    add_option('n1edApiKey', 'N1EDDFLT');
    $apiKey = 'N1EDDFLT';
}
?>


<div id="cms-conf-placeholder"></div>
<div class="n1ed-buttons">
  <button id='n1edSave' type="button" class="n1ed-button n1ed-button--primary"><span>Save</span></button>
</div>
<script src='https://n1ed.com/js/n1ed-cms-conf-3.js'></script>
<script>
var saveFunc = window.attachN1EDCmsConf({
        el: document.getElementById("cms-conf-placeholder"),
        urlSetApiKeyAndToken:  '/wp-json/edsdk-n1ed/v1/saveApi',
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
document.getElementById('n1edSave').addEventListener('click',() => {
  // console.log(funcSave());
  saveFunc((e) => {
      if(e){
        alert('Configuration saved');
      } else {
        alert('Error: configuration was not save');
      }
  });
});
</script>



