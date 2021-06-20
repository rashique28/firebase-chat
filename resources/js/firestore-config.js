// Initialize Firebase
var config = {
    apiKey: "AIzaSyCwNDRLfJt9HT-uaznatofT4H9_EjMuVzo",
    authDomain: "my-chat-project-78b7d.firebaseapp.com",
    databaseURL: "https://my-chat-project-78b7d-default-rtdb.firebaseio.com",
    projectId: "my-chat-project-78b7d",
    storageBucket: "my-chat-project-78b7d.appspot.com",
    messagingSenderId: "104699639888",
    appId: "1:104699639888:web:75f0651f45f1d2e9e9f955"
};

firebase.initializeApp(config);

// Initialize Cloud Firestore through Firebase
var db = firebase.firestore();

// Disable deprecated features
db.settings({
	timestampsInSnapshots: true
});