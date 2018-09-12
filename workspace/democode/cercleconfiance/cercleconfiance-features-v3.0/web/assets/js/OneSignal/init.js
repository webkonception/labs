var OneSignal = window.OneSignal || [];
OneSignal.app_id = '7ff402ef-3ec7-4a10-87a5-bbe1e0c7f8bd';
//OneSignal.push(["init", {
OneSignal.push(function() {
    OneSignal.init({
        appId: OneSignal.app_id,
        language:"fr",
        //requiresUserPrivacyConsent: true,
        autoRegister: false, /* Set to true to automatically prompt visitors */
        httpPermissionRequest: {
            enable: true
        },
        notifyButton: {
            enable: true, /* Required to use the Subscription Bell */
            size: 'medium', /* One of 'small', 'medium', or 'large' */
            theme: 'default', /* One of 'default' (red-white) or 'inverse" (white-red) */
            position: 'bottom-right', /* Either 'bottom-left' or 'bottom-right' */
            offset: {
                bottom: '0px',
                left: '0px', /* Only applied if bottom-left */
                right: '0px' /* Only applied if bottom-right */
            },
            colors: { // Customize the colors of the main button and dialog popup button
                'circle.background': '#1c75bc',
                'circle.foreground': 'white',
                'badge.background': '#1c75bc',
                'badge.foreground': 'white',
                'badge.bordercolor': 'white',
                'pulse.color': 'white',
                'dialog.button.background.hovering': '#c2b499',
                'dialog.button.background.active': '23527c',
                'dialog.button.background': '#1c75bc',
                'dialog.button.foreground': 'white'
            },
            prenotify: true, /* Show an icon with 1 unread message for first-time site visitors */
            showCredit: false, /* Hide the OneSignal logo */
            text: {
                'tip.state.unsubscribed': 'S\'abonner aux notifications',
                'tip.state.subscribed': "Vous êtes abonné aux notifications",
                'tip.state.blocked': "Vous avez bloqué les notifications",
                'message.prenotify': 'Cliquez pour vous désabonner des notifications',
                'message.action.subscribed': "Merci pour votre abonnement !",
                'message.action.resubscribed': "Vous êtes abonné aux notifications",
                'message.action.unsubscribed': "Vous ne recevrez plus de notifications",
                'dialog.main.title': 'Gérer les notifications du site',
                'dialog.main.button.subscribe': 'S\'abonner',
                'dialog.main.button.unsubscribe': 'Se désabonner',
                'dialog.blocked.title': 'Débloquer les notifications',
                'dialog.blocked.message': "Suivez ces instructions pour autoriser les notifications :"
            },
            displayPredicate: function() {
                /*return OneSignal.isPushNotificationsEnabled()
                    .then(function(isEnabled) {
                        // The user is subscribed, so we want to return "false" to hide the Subscription Bell
                        return !isEnabled;
                    });*/
            },
        },
        /* Your other init options here */
        /*welcomeNotification: {
            "title": "Bienvenue !",
            "message": "Merci pour votre visite !",
            // "url": "" // Leave commented for the notification to not open a window on Chrome and Firefox (on Safari, it opens to your webpage)
        },*/
        welcomeNotification: {
            disable: true
        },
        promptOptions: {
            /* actionMessage limited to 90 characters */
            actionMessage: "Nous souhaiterions vous montrer des notifications pour les dernières mises à jour.",
            /* acceptButtonText limited to 15 characters */
            acceptButtonText: "Autoriser",
            /* cancelButtonText limited to 15 characters */
            cancelButtonText: "Non merci"
        }
        //}]);
    });
})
OneSignal.push(function() {
    OneSignal.showHttpPrompt();
});
OneSignal.push(function() {
    OneSignal.on('subscriptionChange', function(isSubscribed) {
        console.info('subscriptionChange');
        if (isSubscribed) {
            console.info('The user is subscribed');
            // The user is subscribed
            //   Either the user subscribed for the first time
            //   Or the user was subscribed -> unsubscribed -> subscribed
        } else {
            console.log("The user is not subscribed yet.");
            OneSignal.registerForPushNotifications();
        }
        OneSignal.getUserId().then(function(userId) {
            // Make a POST call to your server with the user ID
            console.log("OneSignal User ID:", userId);
        });
    });
});

OneSignal.push(["getTags", function(tags) {
    console.log("OneSignal getTags:");
    console.log(tags);
}]);

OneSignal.push(["addListenerForNotificationOpened", function(data) {
    console.log("Received NotificationOpened:");
    console.log(data);
    console.log(data.id);
    console.log(data.heading);
    OneSignal.getUserId( function(userId) {
        console.log("OneSignal User ID:", userId);
        // Make a POST call to Amplitude with the notification data and userId aka playerId
    });
}]);
OneSignal.push(["addListenerForNotificationOpened", function(event) {
    console.log("OneSignal notification clicked:", event);
}]);

OneSignal.push(function() {
    OneSignal.on('notificationDisplay', function (event) {
        console.warn('OneSignal notification displayed:', event);
        /*
        {
            "id": "ce31de29-e1b0-4961-99ee-080644677cd7",
            "heading": "OneSignal Test Message",
            "content": "This is an example notification.",
            "url": "https://onesignal.com?_osp=do_not_open",
            "icon": "https://onesignal.com/images/notification_logo.png"
        }
        */


        OneSignal.getTags().then(function(tags) {
            // All the tags stored on the current webpage visitor
            console.info(tags);
        });
    });
    OneSignal.on('notificationDismiss', function (event) {
        console.warn('OneSignal notification dismissed:', event);
        /*
        {
            "id": "ce31de29-e1b0-4961-99ee-080644677cd7",
            "heading": "OneSignal Test Message",
            "content": "This is an example notification.",
            "url": "https://onesignal.com?_osp=do_not_open",
            "icon": "https://onesignal.com/images/notification_logo.png"
        }
        */
    });
    //OneSignal.addEventListener('received', this.onPushReceived);
    OneSignal.on('received', this.onPushReceived)
    //OneSignal.addEventListener('registered', this.onPushRegistered);
    OneSignal.on('registered', this.onPushRegistered);
    OneSignal.registerForPushNotifications();

    function onPushReceived (notif) {
        console.log('PUSH RECEIVED', notif);
    }

    function onPushRegistered (notifData) {
        console.log('DEVICE PUSH REGISTERED', notifData);
    }

});