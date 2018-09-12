WebFontConfig = {
    //loading: function() {},
    //active: function() {},
    //inactive: function() {},
    //fontloading: function(familyName, fvd) {},
    //fontactive: function(familyName, fvd) {},
    //fontinactive: function(familyName, fvd) {},

    google: {
        families: ['Open Sans:400,300,600,700:all', 'Lato:100,300,400,700']
    },
    timeout: 2000
};
(function(d,v) {
    var wf = d.createElement("script"), s = d.scripts[0];
    wf.src = ('https:' == d.location.protocol ? 'https' : 'http') +
        '://ajax.googleapis.com/ajax/libs/webfont/' + v + '/webfont.js';
    wf.async = 'true';
    //document.head.appendChild(wf);
    s.parentNode.insertBefore(wf, s);
})(document,'1.6.16');