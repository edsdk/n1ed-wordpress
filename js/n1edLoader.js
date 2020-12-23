function includeJS(urlJS, doc, callback) {
  if (!doc) {
    doc = document;
  }
  var scripts = doc.getElementsByTagName("script");
  var alreadyExists = false;
  var existingScript = null;
  for (var i = 0; i < scripts.length; i++) {
    var src = scripts[i].getAttribute("src");
    if (src && src.indexOf(urlJS) !== -1) {
      alreadyExists = true;
      existingScript = scripts[i];
    }
  }
  if (!alreadyExists) {
    var script = doc.createElement("script");
    script.type = "text/javascript";
    if (callback != null) {
      if (script.readyState) {
        // IE
        script.onreadystatechange = function () {
          if (
            script.readyState === "loaded" ||
            script.readyState === "complete"
          ) {
            script.onreadystatechange = null;
            callback(false);
          }
        };
      } else {
        // Others
        script.onload = function () {
          callback(false);
        };
      }
    }
    script.src = urlJS;
    doc.getElementsByTagName("head")[0].appendChild(script);
    return script;
  } else {
    if (callback != null) {
      callback(true);
    }
    return null;
  }
}

// settings = {
//     selector: "#" + this.getId(),
//     urlFileManager: flmngrURL,
//     urlFiles: "/pub/media/wysiwyg/",
//     relative_urls: false,
//     apiKey: this.config.apiKey,
//     token: this.config.token,
//     varienGlobalEvents,
//     configDirectiveGenerator,
//     customDirectiveGenerator,
//     bootstrap4: {
//       includeToGlobalDoc: false,
//     },
//     wysiwyg: this,
//     mwOpts: this.config.plugins[mwi].options,
//     mvOpts: this.config.plugins[mvi].options,
//     external_plugins: {
//       magentowidget: "/extras/plugins/magentowidgets",
//       magentovariable: "/extras/plugins/magentovariables",
//     },
//     toolbar: [
//       "cut copy | undo redo | searchreplace | bold italic strikethrough | forecolor backcolor | blockquote | removeformat | Info",
//       "Flmngr ImgPen | formatselect | link | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | magentowidget magentovariable",
//     ],
document.querySelector(".wp-editor-tabs").style.display = "none";
document.querySelector("#wp-content-media-buttons").style.display = "none";

const { apiKey, token, urlFiles } = n1ed_ajax_object;
console.log(urlFiles);
function deleteInclude() {
  let id = tinymce.editors[0].id;
  tinymce.get(id).remove();
  delete window.tinymce;

  includeJS(`https://cloud.n1ed.com/cdn/${apiKey}/n1tinymce.js`);
  waitForEditor(false, id);
}

function setupNow(editor_id) {
  //   tinymce.editors = [];
  tinymce.init({
    selector: "#" + editor_id,
    urlFileManager: "/wp-json/edsdk-n1ed/v1/flmngr",
    urlFiles,
    apiKey,
    token,
  });
}

function waitForEditor(di = false, editor_id = "") {
  if (window.tinymce) {
    if (di) {
      deleteInclude();
    } else {
      setupNow(editor_id);
    }
  } else {
    setTimeout(function () {
      waitForEditor(di, editor_id);
    }, 100);
  }
}
// console.log(n1ed_ajax_object);
waitForEditor(true, "");
