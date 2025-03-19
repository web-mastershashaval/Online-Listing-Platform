
const firebaseConfig = {
  apiKey: "AIzaSyBnA_euYZYaQcFZFWww_ZGSEhNc7nU7ne0",
  authDomain: "online-listing-system.firebaseapp.com",
  projectId: "online-listing-system",
  storageBucket: "online-listing-system.firebasestorage.app",
  messagingSenderId: "443776638127",
  appId: "1:443776638127:web:23c4b49a345de9fc42885a",
  measurementId: "G-QBC8DYRG8K"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Firebase services
export const auth = firebase.auth();
export const db = firebase.firestore();
export const collection = firebase.firestore();

// export const addDoc = firebase.firestore();
// export const getDocs = firebase.firestore();
// export const query = firebase.firestore();
// export const where = firebase.firestore();


window.auth = auth;
window.db = db;

console.log("Firebase initialized:", firebase.apps.length > 0);
