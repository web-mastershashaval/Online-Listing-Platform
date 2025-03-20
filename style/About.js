import { auth, db } from "../firebase.js";

// DOM Elements
const reviewsContainer = document.getElementById("reviews-container");
const newReviewInput = document.getElementById("new-review");
// Fetch and display reviews on page load
const fetchReviews = async () => {
    try {
        const reviewsSnapshot = await db.collection("reviews").get();
        const reviews = reviewsSnapshot.docs.map(doc => doc.data());

        reviewsContainer.innerHTML = "";
        reviews.forEach(review => {
            const reviewDiv = document.createElement("div");
            reviewDiv.className = "review-container";
            reviewDiv.innerHTML = `
                <p>
                   <strong>By ${review.user || "Anonymous"}:</strong>
                    ${review.text}
                </p>
            `;
            reviewsContainer.appendChild(reviewDiv);
        });
    } catch (error) {
        console.error("Error fetching reviews:", error);
    }
};

// Add a new review
const addReview = async () => {
    const user = auth.currentUser;

    if (!user) {
        alert("You must be logged in to leave a review.");
        return;
    }

    const newReview = newReviewInput.value.trim();
    if (!newReview) {
        alert("Please enter a review before submitting.");
        return;
    }

    try {
        // Fetch the user's name from Firestore
        const userDoc = await db.collection("users").doc(user.uid).get();
        const userName = userDoc.exists ? userDoc.data().name : "Anonymous";

        const reviewData = {
            text: newReview,
            user: userName, 
            createdAt: new Date().toISOString(),
        };

        await db.collection("reviews").add(reviewData);
        newReviewInput.value = ""; // Clear input
        await fetchReviews(); // Refresh reviews
    } catch (error) {
        console.error("Error adding review:", error);
        alert("Error adding review. Please try again.");
    }
};

auth.onAuthStateChanged(async user => {
    if (user) {
        console.log("User logged in:", user);
        try {
            const userDoc = await db.collection("users").doc(user.uid).get();
            if (userDoc.exists) {
                console.log("User details:", userDoc.data());
            }
        } catch (error) {
            console.error("Error fetching user details:", error);
        }
    } else {
        console.log("No user logged in");
        if (window.location.pathname.includes("Home.html")) {
            window.location.href = "login.html";
        }
    }
});

// Event listeners
document.addEventListener("DOMContentLoaded", () => {
    fetchReviews();
    const addReviewBtn = document.getElementById("add-review-btn");
    if (addReviewBtn) {
        addReviewBtn.addEventListener("click", addReview);
    } else {
        console.error("Button with ID 'add-review-btn' not found.");
    }
});

