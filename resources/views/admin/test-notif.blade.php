<html>
<title>Firebase Messaging Demo</title>
<style>
    div {
        margin-bottom: 15px;
    }
</style>
<body>
<div id="token"></div>
<div id="msg"></div>
<div id="notis"></div>
<div id="err"></div>
<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>

<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>
<script>
    MsgElem = document.getElementById("msg")
    TokenElem = document.getElementById("token")
    NotisElem = document.getElementById("notis")
    ErrElem = document.getElementById("err")
    // Initialize Firebase
    // TODO: Replace with your project's customized code snippet
    var config = {
        apiKey: "AIzaSyAiQhyz6woWzHMDyxAR1UM2lmi-pPcbnKc",
        authDomain: "go-4-0-waste.firebaseapp.com",
        databaseURL: "https://go-4-0-waste.firebaseio.com",
        projectId: "go-4-0-waste",
        storageBucket: "go-4-0-waste.appspot.com",
        messagingSenderId: "12749329351"
    };
    firebase.initializeApp(config);

    const messaging = firebase.messaging();
    messaging
        .requestPermission()
        .then(function () {
            MsgElem.innerHTML = "Notification permission granted."
            console.log("Notification permission granted.");

            // get the token in the form of promise
            return messaging.getToken()
        })
        .then(function(token) {
            TokenElem.innerHTML = "token is : " + token
        })
        .catch(function (err) {
            ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
            console.log("Unable to get permission to notify.", err);
        });

    messaging.onMessage(function(payload) {
        console.log("Message received. ", payload);
        NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload)
    });
</script>

<body>

</html>