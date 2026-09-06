// HiddenChat Advanced - Firebase configuration
// NOTE: For production, move privileged secrets/server operations to a backend.
const firebaseConfig = {
  apiKey: "REPLACE_WITH_YOUR_FIREBASE_API_KEY",
  authDomain: "testing-5ce76.firebaseapp.com",
  databaseURL: "https://testing-5ce76-default-rtdb.firebaseio.com",
  projectId: "testing-5ce76",
  storageBucket: "testing-5ce76.firebasestorage.app",
  messagingSenderId: "REPLACE_WITH_YOUR_MESSAGING_SENDER_ID",
  appId: "REPLACE_WITH_YOUR_APP_ID"
};

firebase.initializeApp(firebaseConfig);
const db = firebase.database();
let storage = null;
try { storage = firebase.storage(); } catch(e) {}

const ADMIN_PASSWORD = "CHANGE_THIS_ADMIN_PASSWORD";

function getUser() {
  return JSON.parse(localStorage.getItem("hc_user") || "null");
}
function setUser(name) {
  const uid = "u_" + (crypto.randomUUID ? crypto.randomUUID() : Date.now()+"_"+Math.random().toString(36).slice(2));
  const user = {uid, name: (name || "User").trim().slice(0,40)};
  localStorage.setItem("hc_user", JSON.stringify(user));
  return user;
}
function logout() { localStorage.removeItem("hc_user"); location.href="login.html"; }
function esc(s="") {
  return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
function fmtTime(ts) {
  return new Intl.DateTimeFormat([], {hour:"2-digit", minute:"2-digit"}).format(new Date(ts || Date.now()));
}
function fmtDate(ts) {
  return new Intl.DateTimeFormat([], {day:"2-digit", month:"short", year:"numeric"}).format(new Date(ts || Date.now()));
}
