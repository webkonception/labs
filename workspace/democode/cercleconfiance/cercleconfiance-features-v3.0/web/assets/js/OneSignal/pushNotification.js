OneSignal.push(function() {
    OneSignal.isPushNotificationsEnabled().then(function(isEnabled) {
        if (isEnabled) {
            console.log("Push notifications are enabled!");
            OneSignal.sendSelfNotification(
                /* Title (defaults if unset) */
                website_name,
                /* Message (defaults if unset) */
                msg,
                website_url,
                website_icon,
                {
                    /* Additional data hash */
                    notificationType: notificationType
                },
                buttons
        );

        } else {
            console.log("Push notifications are not enabled yet.");
            OneSignal.registerForPushNotifications();
        }
    });
});