var loadCSS = function(url) {
    var a = document.createElement("link");
    a.href = url;
    a.media = "all";
    a.rel = "stylesheet";
    document.getElementsByTagName("head")[0].appendChild(a);
}

var loadJsAtTop = function(url) {
    var c = document.createElement("script"),
        s = document.scripts[0];
    c.src = url;
    s.parentNode.insertBefore(c, s);
}

var loadFileAtTop = function(url) {
    var script = document.createElement('SCRIPT');
    script.src = url;
    document.getElementsByTagName('HEAD')[0].appendChild(script);
}

var loadFileXHR = function(url) {
    function callback() {
        if (req.readyState == 4) { // 4 = Loaded
            if (req.status == 200) {
                eval(req.responseText);
            } else {
                // Error
            }
        }
    };
    var req = new XMLHttpRequest();
    req.onreadystatechange = callback;
    req.open("GET", url, true);
    req.send("");
}