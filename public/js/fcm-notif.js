
MsgElem = document.getElementById("msg")
TokenElem = document.getElementById("token")
NotisElem = document.getElementById("notis")
ErrElem = document.getElementById("err")
// Initialize Firebase

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
        //save token to database using ajax

    })
    .catch(function (err) {
        ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
        console.log("Unable to get permission to notify.", err);
    });

messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload)
});