/**
 * Created by julien on 13/06/17.
 */
$('document').ready(function () {

    navigator.getUserMedia = navigator.getUserMedia ||
        navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

    function bindEvents(peer) {
        peer.on('error', function (err) {
            console.log(err);
        });
        peer.on('signal', function (data) {
            document.querySelector('#offer').textContent = JSON.stringify(data);
        });
        peer.on('stream', function (stream) {
            let receive = document.querySelector('#receive');
            receive.src = window.URL.createObjectURL(stream);
            receive.play();
        });
        document.querySelector('#incoming').addEventListener('submit', function (e) {
            e.preventDefault();
            peer.signal(JSON.parse(e.target.querySelector('textarea').value));
        });
    }

    function connectPeer(initiator) {
        navigator.getUserMedia({
            video: true,
            audio: true
        }, function (stream) {
            let peer = new SimplePeer({
                initiator: initiator,
                stream: stream,
                trickle: false
            });
            bindEvents(peer);
            let send = document.querySelector('#send');
            send.src = window.URL.createObjectURL(stream);
            send.play();
        }, function () {
        });
    }

    document.querySelector('#start').addEventListener('click', function (e) {
        connectPeer(true);
    });
    document.querySelector('#init').addEventListener('click', function (e) {
        connectPeer(false);
    });

});
