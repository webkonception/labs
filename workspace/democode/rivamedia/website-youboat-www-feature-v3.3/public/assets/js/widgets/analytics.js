/*jslint browser: true*/
/*jslint node: true */
/*global $, ga, window */
window.clSendAnalyticsEvent = function (category, action, label, value) {
    "use strict";
    action = action || "";
    category = category || "Other";
    value = parseInt(value || 1, 10);
    label = label || "undefined";
    try {
        ga('send', 'event', category, action, label, value);
    } catch (ignore) {}
};

$(document).ready(function () {
    "use strict";

    // Put code here to attach listeners to send specific events.
    /** Example
    * $(".cl-sponsor-badge a").on('click', function () {
    *    clSendAnalyticsEvent("Program Tools", "Sponsor", $(this).attr("title"), $(this).data("id"));
    *});
    */
});
