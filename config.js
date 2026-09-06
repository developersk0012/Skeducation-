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
const db=firebase.database();
let storage=null; try{storage=firebase.storage()}catch(e){}
const NS="veil_v2";
const ADMIN_ID="admin";
function uid(){return "u_"+(crypto.randomUUID?crypto.randomUUID():Date.now()+"_"+Math.random().toString(36).slice(2))}
function user(){return JSON.parse(localStorage.getItem("veil_v2_user")||"null")}
function esc(s=""){return String(s).replace(/[&<>"']/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]))}
function time(ts){return new Intl.DateTimeFormat([],{hour:"2-digit",minute:"2-digit"}).format(new Date(ts||Date.now()))}
function day(ts){return new Intl.DateTimeFormat([],{day:"2-digit",month:"short",year:"numeric"}).format(new Date(ts||Date.now()))}
