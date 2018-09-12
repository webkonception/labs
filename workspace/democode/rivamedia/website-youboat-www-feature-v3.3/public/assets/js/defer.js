var externalJs = ['',''];
function addJS(url) {
    var element = document.createElement("script");
    element.src = url;
    document.body.appendChild(element);
}
function downloadJSAtOnload() {
    for (var i = 0, len = externalJs.length; i < len; i++) {
        addJS(externalJs[i])
    }
}
if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;