import { auth, db } from "../firebase.js";

const reviewsContainer = document.getElementById("reviews-container");
const searchForm = document.getElementById("search-form");
const searchedServices = document.getElementById("services");
const searchedLocation = document.getElementById("location");
const searchResultsContainer = document.getElementById("search-results");
const loadingIndicator = document.getElementById("loading-indicator");

const profileButton = document.getElementById("profile-button");
const login = document.getElementById("login");
const logoutButton = document.getElementById("logout-button");
const register = document.getElementById("register");

// Search for users
// const searchUser = async (location, services) => {
//     try {
//         setLoading(true);
//         console.log("Searching for:", { location, services });

//         const usersSnapshot = await db.collection("users")
//             .where("location", "==", location)
//             .where("services", "array-contains-any", services)
//             .get();

//         console.log("Query Snapshot:", usersSnapshot);
//         console.log("Documents Found:", usersSnapshot.docs.length);

//         const users = usersSnapshot.docs.map(doc => ({ ...doc.data(), id: doc.id }));
//         console.log("Users Found:", users);

//         if (users.length === 0) {
//             displayMessage("No users found matching your criteria.");
//         }
//         return users;
//     } catch (error) {
//         console.error("Error searching for users:", error);
//         displayMessage("An error occurred during the search. Please try again later.");
//         return [];
//     } finally {
//         setLoading(false);
//     }
// };


// Display search results

const searchUser = async (location, services) => {
    try {
        setLoading(true);
        console.log("Searching for:", { location, services });

        // First, get users matching the location
        const locationSnapshot = await db.collection("users")
            .where("location", "==", location.toLowerCase())
            .get();

        if (locationSnapshot.empty) {
            displayMessage("No users found in this location.");
            return [];
        }

        let users = locationSnapshot.docs.map(doc => ({ ...doc.data(), id: doc.id }));

        // Filter users manually based on services
        users = users.filter(user =>
            Array.isArray(user.services) &&
            services.some(service => user.services.includes(service))
        );

        console.log("Users Found:", users);

        if (users.length === 0) {
            displayMessage("No users found matching your criteria.");
        }
        return users;
    } catch (error) {
        console.error("Error searching for users:", error);
        displayMessage("An error occurred during the search. Please try again later.");
        return [];
    } finally {
        setLoading(false);
    }
};

// Handle form submission
searchForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const location = searchedLocation.value.trim();
    const services = searchedServices.value.trim().split(",").map(service => service.trim());
  
  console.log("Searching for location:", location);
  console.log("Searching for services:", services);
  
  
    if (!location || services.length === 0 || services[0] === "") {
      displayMessage("Please enter both location and at least one service to search.");
      return;
    }
  
    const users = await searchUser(location, services);
    displaySearchResults(users);
  });


const displaySearchResults = (users) => {
    searchResultsContainer.innerHTML = ""; // Clear previous results
  
    if (users.length === 0) {
      displayMessage("No users found matching your criteria.");
      return;
    }
  
    users.forEach((user) => {
      const createdAt = user.createdAt?.toDate ? user.createdAt.toDate().toLocaleString() : "Unknown"; // Format Firestore timestamp
      
      const userDiv = document.createElement("div");
      userDiv.className = "user-result";
      userDiv.innerHTML = `
        <p><strong>Name:</strong> ${escapeHtml(user.name || "N/A")}</p>
        <p><strong>Services:</strong> ${escapeHtml(Array.isArray(user.services) ? user.services.join(", ") : user.services || "N/A")}</p>
        <p><strong>Location:</strong> ${escapeHtml(user.location || "N/A")}</p>
        <p><strong>Phone:</strong> ${escapeHtml(user.phone || "N/A")}</p>
        <p><strong>Email:</strong> ${escapeHtml(user.email || "N/A")}</p>
        <p><strong>Created At:</strong> ${escapeHtml(createdAt)}</p>
      `;
  
      userDiv.addEventListener("click", () => {
        window.location.href = `profile.html?userId=${user.id}`;
      });
  
      searchResultsContainer.appendChild(userDiv);
    });
  };
  

// Display message
const displayMessage = (message) => {
  searchResultsContainer.innerHTML = `<p class="message">${message}</p>`;
};

// Set loading state
const setLoading = (isLoading) => {
  loadingIndicator.style.display = isLoading ? "block" : "none";
};



// Basic HTML escaping function to prevent XSS
function escapeHtml(unsafe) {
  if (unsafe === null || unsafe === undefined) {
    return "";
  }
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}


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
// Event listeners
document.addEventListener("DOMContentLoaded", () => {
    fetchReviews();
});

//posts
const postsContainer = document.getElementById("postsContainer");

// Render a single post
async function renderPost(postId, postData) {
    // Fetch the user's name from Firestore
    let userName = "Anonymous";
    if (postData.userId) {
        const userDoc = await db.collection("users").doc(postData.userId).get();
        if (userDoc.exists) {
            userName = userDoc.data().name;
        }
    }

    const postDiv = document.createElement("div");
    postDiv.className = "post";
    postDiv.innerHTML = `
        <div>
            <h3>${postData.title}</h3>
            <p>${postData.description}</p>
            ${postData.imageBase64 ? `<img src="${postData.imageBase64}" alt="Post Image" class="post-image" />` : ""}
            <p><strong>Posted by: ${userName}</strong></p>
        </div>
    `;
    postsContainer.appendChild(postDiv);
}

// Load all posts
async function loadAllPosts() {
    postsContainer.innerHTML = ""; // Clear existing posts
    try {
        const querySnapshot = await db.collection("posts").get();
        querySnapshot.forEach((doc) => {
            renderPost(doc.id, doc.data());
        });
    } catch (error) {
        console.error("Error loading posts:", error);
        alert("Failed to load posts. Please try again.");
    }
}

// Load all posts on page load
document.addEventListener("DOMContentLoaded", () => {
    fetchReviews();
    loadAllPosts(); 
});
// Handle logout
logoutButton.addEventListener("click", async () => {
    try {
        await auth.signOut();
        console.log("User logged out successfully");
        window.location.href = "Home.html"; // Redirect to homepage or login page
    } catch (error) {
        console.error("Error logging out:", error);
        alert("Failed to log out. Please try again.");
    }
});

//check if user is logged in
auth.onAuthStateChanged(user => {
    if (user) {
        console.log("User logged in:", user);
        profileButton.style.display = "block";
        login.style.display = "none";
        register.style.display = "none";
        logoutButton.style.display = "block";
    } else {
        console.log("No user logged in");
        profileButton.style.display = "none";
        login.style.display = "block";
        register.style.display = "block";
        logoutButton.style.display = "none";
    }
});