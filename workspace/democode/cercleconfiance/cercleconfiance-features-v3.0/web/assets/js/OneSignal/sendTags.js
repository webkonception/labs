OneSignal.push(function() {
    OneSignal.isPushNotificationsEnabled().then(function(isEnabled) {
        if (isEnabled) {
            console.log("Push notifications are enabled!");
            OneSignal.getUserId( function(userId) {
                // Make a POST call to your server with the user ID
                console.log('userId', userId);
                OneSignal.sendTags({
                    userId: userId,
                    user_type: user_type,
                    user_classifier: user_classifier,
                    user_privileges: user_privileges,
                    user_id_circlename: user_id_circlename,
                    user_id_circle_id: user_id_circle_id,
                    user_id_circle_user_id: user_id_circle_user_id,
                    real_name: real_name,
                    first_name: first_name,
                    last_name: last_name
                    //,salutation: salutation
            }).then(function(tagsSent) {
                    // Callback called when tags have finished sending
                    console.log(tagsSent);
                    OneSignal.setEmail( user_email);
                });
            });
        } else {
            console.log("Push notifications are not enabled yet.");
            OneSignal.registerForPushNotifications();
        }
    });
});