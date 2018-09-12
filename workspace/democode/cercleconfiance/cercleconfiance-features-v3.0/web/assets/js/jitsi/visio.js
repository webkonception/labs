var isMobile = false;
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Opera Mobile|Kindle|Windows Phone|PSP|AvantGo|Atomic Web Browser|Blazer|Chrome Mobile|Dolphin|Dolfin|Doris|GO Browser|Jasmine|MicroB|Mobile Firefox|Mobile Safari|Mobile Silk|Motorola Internet Browser|NetFront|NineSky|Nokia Web Browser|Obigo|Openwave Mobile Browser|Palm Pre web browser|Polaris|PS Vita browser|Puffin|QQbrowser|SEMC Browser|Skyfire|Tear|TeaShark|UC Browser|uZard Web|wOSBrowser|Yandex.Browser mobile/i.test(navigator.userAgent)) isMobile = true;
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
/*function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else {
        var expires = "";
    };
    document.cookie = name+"="+value+expires+"; path=/";
};

function readCookie(name) {
    var nameEQ = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    };
    return null;
};

function eraseCookie(name) {
    createCookie(name,"",-1);
};*/
//////////////////////////////////////////////////////////////////////
// ES6
/*function getCookie(name) {
    let cookie = {};
    decodeURIComponent(document.cookie).split(';').forEach(function(el) {
        let [k,v] = el.split('=');
        cookie[k.trim()] = v;
    })
    return cookie[name];
}*/
//////////////////////////////////////////////////////////////////////
/*var viewMode = readCookie("view-mode");
var viewport = document.querySelector('meta[name="viewport"]');
if(!viewMode || viewMode == "desktop"){
    viewMode = createCookie("view-mode","desktop");
    viewport.setAttribute('content', 'width=1024');
}else if (viewMode == "mobile"){
    viewport.setAttribute('content', 'width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no');
}
*/
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
/**
 * Get the closest matching element up the DOM tree.
 * @private
 * @param  {Element} elem     Starting element
 * @param  {String}  selector Selector to match against
 * @return {Boolean|Element}  Returns null if not match found
 */
var getClosest = function ( elem, selector ) {

    // Element.matches() polyfill
    if (!Element.prototype.matches) {
        Element.prototype.matches =
            Element.prototype.matchesSelector ||
            Element.prototype.mozMatchesSelector ||
            Element.prototype.msMatchesSelector ||
            Element.prototype.oMatchesSelector ||
            Element.prototype.webkitMatchesSelector ||
            function(s) {
                var matches = (this.document || this.ownerDocument).querySelectorAll(s),
                    i = matches.length;
                while (--i >= 0 && matches.item(i) !== this) {}
                return i > -1;
            };
    }

    // Get closest match
    for ( ; elem && elem !== document; elem = elem.parentNode ) {
        if ( elem.matches( selector ) ) return elem;
    }

    return null;

};
//////////////////////////////////////////////////////////////////////
var hasSomeParentTheClass = function (element, classname) {
    // If we are here we didn't find the searched class in any parents node
    if (!element.parentNode) return false;
    // If the current node has the class return true, otherwise we will search
    // it in the parent node
    if (element.className.split(' ').indexOf(classname)>=0) return true;
    return hasSomeParentTheClass(element.parentNode, classname);
};
//////////////////////////////////////////////////////////////////////
var toggleBtnOnOff = function (element) {
    if (hasSomeParentTheClass(element, 'off')) {
        //#//console.log('on');
        element.classList.remove('off');
        element.classList.add('on');
    } else {
        //#//console.log('off');
        element.classList.remove('on');
        element.classList.add('off');
    }
};
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
var api;

var circleMembers = document.getElementById("circleMembers");
var videoButtonJoin = document.getElementById("videoButtonJoin");
var videoButtonCreate = document.getElementById("videoButtonCreate");
//var videoButtonJoin = document.querySelector(".videoButtonJoin");
var videoButtonClose = document.getElementById("videoButtonClose");
var visioContainer = document.getElementById("visioContainer");
var videoParticipants = document.getElementById("videoParticipants");
var videoChat = document.getElementById("videoChat");
var videoEvents = document.getElementById("videoEvents");
var htmlElement = document.querySelector('#Meet_' + _token);
var panelFooter = document.getElementById("panelFooter");
var colChat = document.getElementById("colChat");
var discussion = document.getElementById("discussion");
var toolsVisio = document.getElementById("toolsVisio");

var toggleAudio = document.getElementById('toggleAudio');
var toggleVideo = document.getElementById('toggleVideo');
var toggleFilmStrip = document.getElementById('toggleFilmStrip');
var toggleChat = document.getElementById('toggleChat');
var toggleContactList = document.getElementById('toggleContactList');
var toggleShareScreen = document.getElementById('toggleShareScreen');

var visio_loader = document.getElementById('visio_loader');

var visioRoomLink = document.getElementById('visio_room_link');

// var $toggleAudio = $('#toggleAudio');
// var $toggleVideo = $('#toggleVideo');
// var $toggleFilmStrip = $('#toggleFilmStrip');
// var $toggleChat = $('#toggleChat');
// var $toggleContactList = $('#toggleContactList');
// var $toggleShareScreen = $('#toggleShareScreen');
//////////////////////////////////////////////////////////////////////
var _participantId = '';
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
var interfaceVisioLoad = function(event) {
    console.log('interfaceVisioLoad : ', event);
    visio_loader.classList.add('in');

    var meetContainer = document.querySelector('#Meet_' + _token);
    //meetContainer.innerText = "";
    //visioContainer.style.display = "block";
    //visio_loader.classList.remove('in');
    console.info('api._participants).length', Object.getOwnPropertyNames(api._participants).length);
    console.info('api._participants', api._participants);

    //if (Object.getOwnPropertyNames(api._participants).length > 0) {

    visio_loader.classList.remove('in');
    visioContainer.classList.remove("hidden");
    toolsVisio.classList.remove("hidden");
    panelFooter.classList.add('in');

    console.info('api._participants', api._participants);
    api.executeCommand('avatarUrl', _avatarURL);
    api.executeCommand('displayName', _displayName);

    //videoButton.innerText = "Fermer la viso-conférence";

    //api.executeCommand('displayName', _userName);
    //api.executeCommand('formattedDisplayName', _formattedDisplayName);

    //videoButtonJoin.classList.add("hidden");
    //getClosest(videoButtonJoin, '.row').classList.add("hidden");
    document.getElementById('visioBtns').classList.add("hidden");
    document.getElementById('panelHeading').classList.add("hidden");
    videoButtonClose.classList.remove("hidden");

    if (colChat) {
        colChat.classList.remove("hidden");
    }
    if (discussion) {
        discussion.classList.add('in');
    }

    toggleAudio.addEventListener("click", (event) => { // Mutes / unmutes the audio for the local participant. No arguments are required.
        api.executeCommand('toggleAudio');
        var parent = getClosest(event.target, '.btn');
        toggleBtnOnOff(parent);
    }, false);

    toggleVideo.addEventListener("click", (event) => { // Mutes / unmutes the video for the local participant. No arguments are required.
        console.log('toggleVideo');
        api.executeCommand('toggleVideo');
        var parent = getClosest(event.target, '.btn');
        toggleBtnOnOff(parent);
    }, false);

    toggleFilmStrip.addEventListener("click", (event) => { // Hides / shows the filmstrip. No arguments are required.
        api.executeCommand('toggleFilmStrip');
        var parent = getClosest(event.target, '.btn');
        toggleBtnOnOff(parent);
    }, false);

    toggleChat.addEventListener("click", (event) => { // Hides / shows the chat. No arguments are required.
        api.executeCommand('toggleChat');
        var parent = getClosest(event.target, '.btn');
        toggleBtnOnOff(parent);
    }, false);

    toggleContactList.addEventListener("click", (event) => { // Hides / shows the contact list. No arguments are required.
        api.executeCommand('toggleContactList');
        var parent = getClosest(event.target, '.btn');
        toggleBtnOnOff(parent);
    }, false);

    toggleShareScreen.addEventListener("click", (event) => { // Starts / stops screen sharing. No arguments are required.
        api.executeCommand('toggleShareScreen');
        var parent = getClosest(event.target, '.btn');
        toggleBtnOnOff(parent);
    }, false);

    //var videoParticipantName = '';
    api.addEventListener("participantJoined", (data) => {
        console.log("participantJoined");

        videoParticipants.innerText = api.getNumberOfParticipants();
        videoParticipants.classList.add('in');
        var participantId = data.id;
        var displayName = data.displayName;
        var avatarURL = api.getAvatarURL(participantId);
        console.log("participantJoined", participantId, displayName, avatarURL);
        //api.executeCommand('displayName', _displayName);
        circleMembers.querySelector('a[data-displayName="' + displayName + '"]').setAttribute('data-participantId', participantId);
        circleMembers.querySelector('a[data-participantId="' + participantId + '"]').classList.add('bg-info');
        //circleMembers.querySelector('a[data-participantId="'+participantId+'"]').querySelector('.label').classList.add('label-primary');
        var eventMsg = '"' + displayName + '"' + ' a rejoint la visio';
        videoEvents.value = eventMsg;
    }, false);

    // videoConferenceJoined
    /*
    {
        "roomName": room, // the room name of the conference
        "id": id, // the id of the local participant
        "displayName": displayName, // the display name of the local participant
        "avatarURL": avatarURL // the avatar URL of the local participant
    }
    */
    api.addEventListener("videoConferenceJoined", (data) => {
        console.log("videoConferenceJoined");
        visio_loader.classList.remove('in');
        document.querySelectorAll('#circleMembers input[type="checkbox"]:checked').forEach(function (element) {
            element.setAttribute('disabled', 'disabled');
        });
        //videoParticipantName = document.getElementById("videoParticipantName").value;
        //api.executeCommand('displayName', videoParticipantName);
        videoParticipants.innerText = api.getNumberOfParticipants();
        videoParticipants.classList.add('in');
        //console.log("videoConferenceJoined", videoParticipantName);
        var roomName = data.roomName;
        var participantId = data.id;
        var displayName = data.displayName; // api.getDisplayName(participantId);
        var avatarURL = data.avatarURL;
        console.log("videoConferenceJoined", roomName, participantId, displayName, avatarURL);
        //api.executeCommand('displayName', _displayName);
        _participantId = participantId;
        circleMembers.querySelector('a[data-displayName="' + displayName + '"]').setAttribute('data-participantId', participantId);
        circleMembers.querySelector('a[data-participantId="' + participantId + '"]').classList.add('bg-success');
        //circleMembers.querySelector('a[data-participantId="'+participantId+'"]').querySelector('.label').classList.add('label-primary');
        var eventMsg = '"' + displayName + '"' + ' a rejoint la visio';
        videoEvents.value = eventMsg;
    }, false);

    api.addEventListener("participantLeft", (data) => {
        videoParticipants.innerText = api.getNumberOfParticipants();
        videoParticipants.classList.add('in');
        participantId = data.id;
        var displayName = api.getDisplayName(participantId);
        console.log(api.getDisplayName(participantId));
        var avatarURL = api.getAvatarURL(participantId);
        console.log("participantLeft", participantId, displayName, avatarURL);
        var eventMsg = '"' + displayName + '"' + ' a quitté la visio';
        videoEvents.value = eventMsg;
    }, false);

    api.addEventListener("videoConferenceLeft", (data) => {
        /*/*meetContainer.innerText = "";*/
        videoParticipants.innerText = api.getNumberOfParticipants();
        videoParticipants.classList.add('in');
        roomName = data.roomName;
        console.log("videoConferenceLeft", roomName);
        var eventMsg = 'Vous avez quitté la visio-conférence ';
        videoEvents.value = eventMsg;
    }, false);

    // incomingMessage
    /*{
        "from": from, // The id of the user that sent the message
        "nick": nick, // the nickname of the user that sent the message
        "message": txt // the text of the message
    }*/
    api.addEventListener("incomingMessage", (data) => {
        //#//console.log(data);
        var from = data.from;
        var nick = data.nick;
        var message = data.message;
        //#//console.log("incomingMessage", from, nick, message);
        //var timestamp = Date.now();
        //var date = new Date(timestamp).toLocaleString();
        //var chatMsg = '['+ date +']' + ' "' + nick.replace(' ('+from+')', '') +'"' + ' > ' + message;
        var displayName = nick.replace(' (' + from + ')', '');
        var chatMsg = '<strong>"' + displayName + '"</strong>' + ' > ' + message;
        if (typeof videoChat != undefined) {
            videoChat.value = chatMsg;
        }
        var msgContainer = document.createElement("li");
        msgContainer.className = "list-group-item";
        if (displayName === _displayName) {
            msgContainer.classList.add('active');
        }
        msgContainer.innerHTML = chatMsg;
        if (typeof videoChat != undefined) {
            videoChat.appendChild(msgContainer);
        }
        var eventMsg = '"' + displayName + '"' + ' écrit';
        videoEvents.value = eventMsg;
    }, false);

    // outgoingMessage
    /*{
        "message": txt // the text of the message
    }*/

    api.addEventListener("outgoingMessage", (data) => {
        //#//console.log(data);
        var from = data.from;
        var nick = data.nick;
        var message = data.message;
        //#//console.log("outgoingMessage", from, nick, message);
        var chatMsg = '#> ' + message;
        if (typeof videoChat != undefined) {
            videoChat.value = chatMsg;
        }
        var msgContainer = document.createElement("li");
        msgContainer.className = "list-group-item active";
        msgContainer.innerHTML = chatMsg;
        if (typeof videoChat != undefined) {
            videoChat.appendChild(msgContainer);
        }
        var eventMsg = '"le message "' + message + '"' + '" est envoyé ';
        videoEvents.value = eventMsg;
    }, false);
    // audioMuteStatusChanged
    /*
    {
    "muted": muted // new muted status - boolean
    }
     */
    api.addEventListener("audioMuteStatusChanged", (data) => {
        var muted = data.muted;
        console.log("audioMuteStatusChanged", muted);
        var eventMsg = 'le son est ';
        if (muted) {
            toggleAudio.classList.add('off');
            eventMsg += 'désactivé';
        } else {
            toggleAudio.classList.remove('off');
            eventMsg += 'activé';
        }
        videoEvents.value = eventMsg;
    }, false);

    // videoMuteStatusChanged
    /*
    {
    "muted": muted // new muted status - boolean
    }
     */
    api.addEventListener("videoMuteStatusChanged", (data) => {
        var muted = data.muted;
        console.log("videoMuteStatusChanged", muted);
        var eventMsg = 'la vidéo est ';
        if (muted) {
            toggleVideo.classList.add('off');
            eventMsg += 'désactivé';
        } else {
            toggleVideo.classList.remove('off');
            eventMsg += 'activé';
        }
        videoEvents.value = eventMsg;
    }, false);

    api.addEventListener("readyToClose", (data) => {
        console.log("interfaceVisioLoad readyToClose");
    }, false);
    //} else {
    //if (Object.getOwnPropertyNames(api._participants).length < 0) {

    document.querySelectorAll('.btn-reload').forEach(function(element) {
        element.addEventListener("click", (event) => { // Mutes / unmutes the audio for the local participant. No arguments are required.
            window.location.reload();
        }, false);
    });
    if(isMobile) {
        var iframe = api.getIFrame();
        visioRoomLink.classList.remove("hidden");
        var appURL = iframe.src.replace('https://','org.jitsi.meet://');
        //visioRoomLink.querySelector('a').setAttribute('href', encodeURI(appURL))
        visioRoomLink.querySelector('a').setAttribute('href', appURL)

        //var strWindowFeatures = "menubar=no,location=no,resizable=yes,scrollbars=no,status=no";
        //var appWindow;
        //appWindow = window.open(appURL, "CercleConfianceVisio", strWindowFeatures);
        //window.open(appURL, '_blank', false);
        //window.location = appURL;
        visioContainer.querySelector('iframe').setAttribute('src', appURL);
        //visioRoomLink.querySelector('a').click();
        /*setTimeout(function() {
            window.location = appURL; }, 4000
        );*/

        visioContainer.classList.add("hidden");
        toolsVisio.classList.add("hidden");
        panelFooter.classList.remove('in');

        //getClosest(videoButtonJoin,'.row').classList.add("hidden");
        document.getElementById('visioBtns').classList.add("hidden");
        document.getElementById('panelHeading').classList.add("hidden");

    }
}
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
var options = {
    roomName: _roomName,
    displayName: _displayName, // the display name of the local participant
    avatarURL: _avatarURL, // the avatar URL of the local participant
    width: '100%',
    height: 450,
    parentNode: _parentNode,
    interfaceConfigOverwrite: {
        APP_NAME: _APP_NAME,
        //MOBILE_APP_PROMO:false,
        TOOLBAR_BUTTONS: _TOOLBAR_BUTTONS,
        DEFAULT_REMOTE_DISPLAY_NAME: _DEFAULT_REMOTE_DISPLAY_NAME,
        DEFAULT_LOCAL_DISPLAY_NAME: 'moi',
        DEFAULT_BACKGROUND: ['#C2B499'],
        SHOW_JITSI_WATERMARK: false,
        //JITSI_WATERMARK_LINK: 'https://jitsi.org',

        // if watermark is disabled by default, it can be shown only for guests
        SHOW_WATERMARK_FOR_GUESTS: false,
        SHOW_BRAND_WATERMARK: false,
        //BRAND_WATERMARK_LINK: 'http://vps433266.ovh.net/',
        LANG_DETECTION: true,
        filmStripOnly: _filmStripOnly,

        MOBILE_APP_PROMO: true, // to remove links to app
        //http://org.jitsi.meet//meet.jit.si/

        /**
         * Specify custom URL for downloading android mobile app.
         */
        MOBILE_DOWNLOAD_LINK_ANDROID: 'https://play.google.com/store/apps/details?id=org.jitsi.meet',

        /**
         * Specify URL for downloading ios mobile app.
         */
        MOBILE_DOWNLOAD_LINK_IOS: 'https://itunes.apple.com/us/app/jitsi-meet/id1165103905',

        APP_SCHEME: 'cercle-confiance.fr'
    },
    configOverwrite: {
        enableRtpStats: false, // Enables RTP stats processing
        disableStats: true,
        enableWelcomePage: false,
        disableSimulcast: false,
        logStats: false,

        //startAudioOnly: true,
        // Every participant after the Nth will start audio muted.
        // startAudioMuted: 10,

        // Start calls with audio muted. Unlike the option above, this one is only
        // applied locally. FIXME: having these 2 options is confusing.
        startWithAudioMuted: false,

        // Every participant after the Nth will start video muted.
        // startVideoMuted: 10,

        // Start calls with video muted. Unlike the option above, this one is only
        // applied locally. FIXME: having these 2 options is confusing.
        startWithVideoMuted: false,

        // Enabling the close page will ignore the welcome page redirection when
        // a call is hangup.
        enableClosePage: false,

        // Default language for the user interface.
        defaultLanguage: 'fr',

        // Message to show the users. Example: 'The service will be down for
        // maintenance at 01:00 AM GMT,
        noticeMessage: 'Bienvenue !',

        // The Google Analytics Tracking ID
        // googleAnalyticsTrackingId = 'your-tracking-id-here-UA-123456-1',
    },
    onload: function (event) {
        interfaceVisioLoad(event);
    }
};
//////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
var visioName = function(event) {
    var member_id = '';
    var visioMultiple = document.getElementById('visioMultiple');
    if($(event.target).is(':checked')) {
        //#//var roomName = document.getElementById('videoButtonCreate').getAttribute('data-roomname');
        //#//roomName += '_' + event.target.roomName;
        //#//document.getElementById('videoButtonCreate').setAttribute('data-roomname', roomName);

        event.target.parentNode.querySelector('a').classList.add("bg-info");

        if(document.getElementById("membersList")) {
            document.getElementById("membersList").remove();
        }

        var membersList = document.createElement('div');
        membersList.setAttribute('id', "membersList");

        document.querySelectorAll('#circleMembers input[type="checkbox"]:checked').forEach(function(element, index) {
            member_id = element.getAttribute('data-member_id');
            var div = document.createElement('span');
            div.setAttribute('data-member_id', member_id);
            div.innerHTML = element.getAttribute('value');
            div.classList.add("label");
            var label = index === 0 ? 'label-primary' : 'label-info';
            div.classList.add(label);
            membersList.appendChild(div);
        });

        //visioMultiple.querySelector('.alert').appendChild(membersList);
        visioMultiple.appendChild(membersList);

        /*
        var div = document.createElement('span');
        div.setAttribute('data-member_id', member_id);
        div.innerHTML = event.target.getAttribute('value');
        div.classList.add("label");
        div.classList.add("label-info");
        visioMultiple.querySelector('.alert').appendChild(div);
        */
    } else {
        //#//console.log(event);
        member_id = event.target.getAttribute('data-member_id');
        //#//console.log(member_id);
        event.target.parentNode.querySelector('a').classList.remove("bg-info");
        if(visioMultiple.querySelector('[data-member_id="' + member_id + '"]')) {
            visioMultiple.querySelector('[data-member_id="' + member_id + '"]').remove();
        }
    }

    var roomName = document.getElementById('videoButtonJoin').getAttribute('data-roomname');
    document.querySelectorAll('#circleMembers input[type="checkbox"]:checked').forEach(function(element) {
        member_id = element.getAttribute('data-member_id');
        roomName += '_' + member_id;
        document.getElementById('videoButtonCreate').setAttribute('data-roomname', roomName);
    });

    var parent = getClosest(videoButtonJoin,'#visioBtns');
    if($(circleMembers).find('input[type="checkbox"]:checked').not('[disabled="disabled"]').length > 0) {
        parent.classList.remove("all");
        parent.classList.add("multiple");
        videoButtonCreate.classList.remove("disabled");
        videoButtonJoin.classList.add("disabled");
    } else {
        parent.classList.remove("multiple");
        parent.classList.add("all");
        videoButtonCreate.classList.add("disabled");
        videoButtonJoin.classList.remove("disabled");
        if(document.getElementById("membersList")) {
            document.getElementById("membersList").remove();
        }
    }
    var btnCreate = document.getElementById("videoButtonCreate");
    btnCreate.roomName = btnCreate.getAttribute('data-roomName');
    options.roomName = btnCreate.roomName;
    btnCreate.addEventListener("click", visioLaunch, false);
};
var elementsCheckbox = document.querySelectorAll('#circleMembers input[type="checkbox"]');
for (var i = 0; i < elementsCheckbox.length; i++) {
    elementsCheckbox[i].addEventListener("click", visioName, false);
}

var elementsAvatarsLink = document.querySelectorAll('#circleMembers input[type="checkbox"]+label+a');
for (var i = 0; i < elementsAvatarsLink.length; i++) {
    elementsAvatarsLink[i].addEventListener("click", function(event){
        var parent = getClosest(event.target,'li');
        parent.querySelector('input[type="checkbox"]').click();
    }, false);
}

var visioLaunch = function(event) {
    AdapterJS.webRTCReady(function(isUsingPlugin) {
        // The WebRTC API is ready.
        //isUsingPlugin: true is the WebRTC plugin is being used, false otherwise
        /*getUserMedia(constraints, successCb, failCb);*/

        /* btnReload */
        if(document.getElementById('btnReload')) {
            document.getElementById('btnReload').remove();
        }
        var li=document.createElement("li");
        var btnReload=document.createElement("button");
        btnReload.setAttribute('id','btnReload');
        btnReload.setAttribute('class','btn btn-default on');
        btnReload.setAttribute('title','Recharger');
        btnReload.innerHTML = '<span class="fa-stack fa-lg"><i class="fa fa-refresh fa-stack-1x"></i></span>';
        li.appendChild(btnReload);
        toolsVisio.querySelector('ul').appendChild(li);

        btnReload.addEventListener("click", (event) => { // Mutes / unmutes the audio for the local participant. No arguments are required.
            var button = document.querySelectorAll('.videoButtonJoin:not(.disabled)');
            api.executeCommand('hangup');
            api.removeEventListeners(["participantJoined", "participantLeft", "videoConferenceJoined", "videoConferenceLeft"]);
            api.dispose();
            $(button).trigger('click');
        }, false);

        //#//console.info('options',options);
        if (event.target.roomName) {
            options.roomName = event.target.roomName;
        }
        if(!options.roomName) {
            $(btnReload).trigger('click');
        }
        if ( options.roomName && !visioContainer.style.display || visioContainer.style.display === "none" ) {
            //api = new JitsiMeetExternalAPI(domain, room, width, height, htmlElement, configOverwrite, interfaceConfigOverwrite);
            api = new JitsiMeetExternalAPI(domain, options);
            console.info('api._participants', api._participants);
            //api.executeCommand('avatarUrl', _avatarURL);
            //api.executeCommand('displayName', _displayName);


        } else {
            api.executeCommand('hangup');
            console.log("else hangup");

            api.addEventListener("readyToClose", (data) => {
                console.log("hangup videoButtonClose readyToClose");
                videoParticipants.innerText = "0";
                videoParticipants.classList.remove('in');

                api.removeEventListeners(["participantJoined", "participantLeft", "videoConferenceJoined", "videoConferenceLeft"]);
                api.dispose();

                //visioContainer.style.display = "none";
                visioContainer.classList.add("hidden");

                visioRoomLink.classList.add("hidden");

                //videoButtonJoin.classList.remove("hidden");
                //getClosest(videoButtonJoin,'.row').classList.remove("hidden");
                document.getElementById('visioBtns').classList.remove("hidden");
                document.getElementById('panelHeading').classList.remove("hidden");
                videoButtonClose.classList.add("hidden");

                toolsVisio.classList.add("hidden");
                panelFooter.classList.remove('in');

                if(colChat) {
                    colChat.classList.add("hidden");
                }
                if(discussion) {
                    discussion.classList.remove('in');
                }

            }, false);
        }
    });
};

videoButtonClose.addEventListener("click", () => {
    api.executeCommand('hangup');
    //#//console.log("videoButtonClose hangup");

    api.addEventListener("readyToClose", (data) => {
        //#//console.log("videoButtonClose readyToClose");

        api.removeEventListeners(["participantJoined", "participantLeft", "videoConferenceJoined", "videoConferenceLeft"]);
        api.dispose();

        videoParticipants.innerText = "0";
        videoParticipants.classList.remove('in');

        //visioContainer.style.display = "none";
        visioContainer.classList.add("hidden");

        visioRoomLink.classList.add("hidden");

        //videoButtonJoin.classList.remove("hidden");
        //getClosest(videoButtonJoin,'.row').classList.remove("hidden");
        document.getElementById('visioBtns').classList.remove("hidden");
        document.getElementById('panelHeading').classList.remove("hidden");
        videoButtonClose.classList.add("hidden");

        toolsVisio.classList.add("hidden");
        panelFooter.classList.remove('in');

        if(colChat) {
            colChat.classList.add("hidden");
        }
        if(discussion) {
            discussion.classList.remove('in');
        }

        document.querySelectorAll('#circleMembers input[type="checkbox"]:checked').forEach(function(element) {
            element.removeAttribute('disabled','disabled');
            $(element).trigger('click');
        });
        document.querySelectorAll('#circleMembers input[type="checkbox"].current_user').forEach(function(element) {
            $(element).trigger('click');
            element.setAttribute('disabled','disabled');
        });
        window.location.reload();

    }, false);
}, false);

var elements = document.querySelectorAll(".videoButtonJoin");
for (var i = 0; i < elements.length; i++) {
    elements[i].roomName = elements[i].getAttribute('data-roomName');
    elements[i].addEventListener("click", visioLaunch, false);
}

/*videoButtonClose.addEventListener("click", () => {
    api.executeCommand('hangup');
    //videoButtonJoin.classList.remove("hidden");
    getClosest(videoButtonJoin,'.row').classList.remove("hidden");
    videoButtonClose.classList.add("hidden");

    //visioContainer.style.display = "none";
    visioContainer.classList.add("hidden");
    //videoButton.innerText = "Rejoindre la visio-conférence";

    toolsVisio.classList.add("hidden");
    panelFooter.classList.remove('in');

    if(colChat) {
      colChat.classList.add("hidden");
    }
    if(discussion) {
      discussion.classList.remove('in');
    }
}, false);*/

//var api = new JitsiMeetExternalAPI(domain, options);
//api.executeCommand('avatarUrl', '{% if circleUser.user.avatar != null %}{{ asset("https://cercle-confiance.fr/uploads/" ~ circleUser.user.avatar) }}{% else %}{{ asset("https://cercle-confiance.fr/assets/img/avatar.png") }}{% endif %}');
