// VeilChat — NEW standalone app
// Uses the TESTING Firebase project only.
// Firebase values are from the testing project; no SK Education database is used.
const firebaseConfig = {
  apiKey: "AIzaSyBJA-6ulrkajXAQZCNvR06_Lr-l9GxYPM8",
  authDomain: "testing-5ce76.firebaseapp.com",
  databaseURL: "https://testing-5ce76-default-rtdb.firebaseio.com",
  projectId: "testing-5ce76",
  storageBucket: "testing-5ce76.firebasestorage.app",
  messagingSenderId: "236048523039",
  appId: "1:236048523039:android:2b77e9729a8fe975f75a2a"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.database();
let storage = null;
try { storage = firebase.storage(); } catch(e) { console.warn("Storage unavailable",e); }
