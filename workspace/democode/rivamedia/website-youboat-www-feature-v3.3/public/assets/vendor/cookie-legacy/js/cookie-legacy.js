(function () {
    var Cookie = {
        get: function (name) {
            var cookies = document.cookie.split(';');
            name += "=";
            for (var i = 0, n = cookies.length; i < n; i++) {
                var c = cookies[i];
                while (' ' === c.charAt(0)) {
                    c = c.substring(1);
                }
                if (0 === c.indexOf(name)) {
                    return unescape(c.substring(name.length));
                }
            }
            return null;
        },
        set: function (name, value, days, path, domain, secure) {
            var expires;
            if ('number' === typeof days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = date.toGMTString();
            }

            document.cookie = name + "=" + escape(value) +
              ((expires) ? "; expires=" + expires : "") +
              ((path) ? "; path=" + path : "") +
              ((domain) ? "; domain=" + domain : "") +
              ((secure) ? "; secure" : "");
        },
        unset: function (name) {
            this.set(name, '', 0);
        }
    };
    $(document).ready(function() {
        var cookiePolicy = Cookie.get('cookie_policy');
        var $CookieDiv = $('#cookie_policy');
        if ('validated' !== cookiePolicy) {
            if ($CookieDiv.length > 0) {
                //
            } else {
                var cookieDiv = document.createElement("div");
                cookieDiv.id = 'cookie_policy';

                var theBody = document.getElementsByTagName('body')[0];
                theBody.insertBefore(cookieDiv, theBody.firstChild);

                cookieDiv.innerHTML = '<div class="row">'
                    + '<div class="col-sm-7 col-sm-offset-1 cookie-text"> En poursuivant votre navigation sur le site, vous acceptez l\'utilisation des cookies pour vous proposer notamment des publicités ciblées en fonction de vos centres d\'intérêt. </div>'
                    + '<div class="col-sm-3 cookie-btn"><a class="btn btn-primary btn-block close" onclick="return false;">OK</a></div>'
                    + '</div>';
                var $CookieDiv = $('#cookie_policy');
            }
            $('a.close', $CookieDiv).on("click", function (event) {
                event.preventDefault();
                Cookie.unset("cookie_policy");
                Cookie.set("cookie_policy", 'validated', 365, "\/", window.location.host, false);
                $CookieDiv.remove();
            });
        } else {
            $CookieDiv.remove();
        }
    });
})();
