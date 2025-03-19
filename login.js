import { auth } from "../firebase.js";

// Debugging Firebase services (optional)
console.log("Auth service:", auth);

document.getElementById("login-form").addEventListener("submit", logIn);

function logIn(event) {
    event.preventDefault();
    
    const email = document.getElementById("login-email").value.trim();
    const password = document.getElementById("login-password").value.trim();
    const errorMessageElement = document.getElementById("error-message");

    // Clear any existing error messages
    errorMessageElement.style.display = "none";
    errorMessageElement.textContent = "";

    if (!email || !password) {
        errorMessageElement.style.display = "block";
        errorMessageElement.textContent = "Please enter both email and password.";
        return;
    }

    auth.signInWithEmailAndPassword(email, password)
        .then(userCredential => {
            console.log("User logged in:", userCredential.user);
            // Redirect to home page
            window.location.href = "../Home.html";
        })
        .catch(error => {
            console.error("Login error:", error.message);
            errorMessageElement.style.display = "block";
            errorMessageElement.textContent = "Error: " + error.message;
        });
}

auth.onAuthStateChanged(user => {
    if (user) {
        console.log("User logged in:", user);
    } else {
        console.log("No user logged in");
        if (window.location.pathname.includes("Home.html")) {
            window.location.href = "login.html";
        }
    }
});
