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

// document.querySelector(".wp-editor-tabs").style.display = "none";
// document.querySelector("#wp-content-media-buttons").style.display = "none";

var apiKey = n1ed_ajax_object.apiKey;
var token = n1ed_ajax_object.token;
var urlFiles = n1ed_ajax_object.urlFiles;

function setupNow(editor_id) {
  tinymce.init({
    selector: "#" + editor_id,
    urlFileManager: "/wp-json/edsdk-n1ed/v1/flmngr",
    urlFiles: urlFiles,
    integration: "wordpress",
    apiKey: apiKey,
    token: token,
    relative_urls: false,
  });
}

function waitForEditor(di, editor_id) {
  if (window.tinymce) {
    if (di && tinymce.editors[0] && tinymce.editors[0].id) {
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

includeJS(
  "https://cloud.n1ed.com/cdn/" + apiKey + "/n1tinymce.js",
  document,
  function () {
    waitForEditor(false, "content");
  }
);
