import { auth, db } from "../firebase.js";
import { createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-auth.js";
import { doc, setDoc } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", () => {
    const customerForm = document.getElementById("customer-signup-form");
    const professionalForm = document.getElementById("professional-signup-form");

    const registerUser = async (form, type) => {
        const name = form.querySelector("input[id$='name']").value;
        const location = form.querySelector("input[id$='location']").value;
        const phone = form.querySelector("input[id$='phone']").value;
        const email = form.querySelector("input[id$='email']").value;
        const password = form.querySelector("input[id$='password']").value;
        const service = type === "professional" ? form.querySelector("input[id$='service']").value : null;

        try {
            const userCredential = await createUserWithEmailAndPassword(auth, email, password);
            const user = userCredential.user;

            // ✅ Correct way to reference a document
            await setDoc(doc(db, "users", user.uid), {
                name,
                location,
                phone,
                email,
                type,
                service: service || "",
                uid: user.uid
            });

            alert("Registration successful!");
            window.location.href = "home.html";
        } catch (error) {
            form.querySelector(".error").textContent = error.message;
        }
    };

    customerForm.addEventListener("submit", (e) => {
        e.preventDefault();
        registerUser(customerForm, "users");
    });

    professionalForm.addEventListener("submit", (e) => {
        e.preventDefault();
        registerUser(professionalForm, "professional");
    });
});
