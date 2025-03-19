import { auth, db } from "../firebase.js";

const profileForm = document.getElementById("profileForm");
const profilePicture = document.getElementById("display-profile-picture");
const nameInput = document.getElementById("name");
const locationInput = document.getElementById("location");
const phoneInput = document.getElementById("phone");
const serviceInput = document.getElementById("service");
const profilePicInput = document.getElementById("profilePic");
const editButton = document.getElementById("editButton");
const updateButton = document.getElementById("updateButton");
const profilePicLabel = document.getElementById("profilePicLabel");

// Display Elements
const displayName = document.getElementById("display-name");
const displayLocation = document.getElementById("display-location");
const displayPhone = document.getElementById("display-phone");
const displayService = document.getElementById("display-service");
const displayEmail = document.getElementById("display-email");

// Load user profile
auth.onAuthStateChanged(async (user) => {
    if (user) {
        try {
            const userDoc = await db.collection("users").doc(user.uid).get();
            const userData = userDoc.data();

            if (userData && profilePicture) {
                profilePicture.src = userData.profilePicture || "images/background.jpeg";
                displayName.textContent = userData.name || "Not provided";
                displayLocation.textContent = userData.location || "Not provided";
                displayPhone.textContent = userData.phone || "Not provided";
                displayService.textContent = userData.service || "Not provided";
                displayEmail.textContent = user.email || "Not provided";
            }
        } catch (error) {
            console.error("Error fetching user data:", error);
            alert("Failed to load profile. Please try again.");
        }
    } else {
        alert("No user is logged in!");
        window.location.href = "register.html";
    }
});

// Enable editing
function enableEditing() {
    document.getElementById("view-mode").style.display = "none";
    profileForm.style.display = "block";

    nameInput.removeAttribute("readonly");
    locationInput.removeAttribute("readonly");
    phoneInput.removeAttribute("readonly");
    serviceInput.removeAttribute("readonly");

    profilePicInput.style.display = "block";
    profilePicLabel.style.display = "block";
    editButton.style.display = "none";
    updateButton.style.display = "block";
}

// Update user profile
profileForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const user = auth.currentUser;
    if (!user) {
        alert("No user is logged in!");
        return;
    }

    const name = nameInput.value.trim();
    const location = locationInput.value.trim();
    const phone = phoneInput.value.trim();
    const service = serviceInput.value.trim();
    const profilePicFile = profilePicInput.files[0];

    try {
        let profilePicURL = profilePicture.src;

        // Handle profile picture upload
        if (profilePicFile) {
            const reader = new FileReader();
            reader.onload = async (e) => {
                const base64Image = e.target.result;
                await updateProfile(user.uid, name, location, phone, service, base64Image);
            };
            reader.readAsDataURL(profilePicFile);
        } else {
            await updateProfile(user.uid, name, location, phone, service, profilePicURL);
        }
    } catch (error) {
        console.error("Error updating profile:", error);
        alert("Failed to update profile. Please try again.");
    }
});

// Update profile in Firestore
async function updateProfile(uid, name, location, phone, service, profilePicture) {
    try {
        await db.collection("users").doc(uid).set({
            name,
            location,
            phone,
            service,
            profilePicture,
        }, { merge: true });

        alert("Profile updated successfully!");
        window.location.reload();
    } catch (error) {
        console.error("Error updating profile:", error);
        alert("Failed to update profile. Please try again.");
    }
}

window.enableEditing = enableEditing;

// posts section
const newPostForm = document.getElementById("newPostForm");
const postTitle = document.getElementById("postTitle");
const postDescription = document.getElementById("postDescription");
const postImageInput = document.getElementById("postImage");
const postsContainer = document.getElementById("postsContainer");

// Load posts for the current user
auth.onAuthStateChanged(async (user) => {
    if (user) {
        loadUserPosts(user.uid);
    } else {
        alert("No user logged in!");
        window.location.href = "register.html";
    }
});

// Load user posts
async function loadUserPosts(userId) {
    postsContainer.innerHTML = ""; // Clear existing posts
    try {
        const querySnapshot = await db.collection("posts").where("userId", "==", userId).get();
        querySnapshot.forEach((doc) => {
            renderPost(doc.id, doc.data());
        });
    } catch (error) {
        console.error("Error loading posts:", error);
        alert("Failed to load posts. Please try again.");
    }
}

// Render a single post
function renderPost(postId, postData) {
    const postDiv = document.createElement("div");
    postDiv.className = "post";
    postDiv.innerHTML = `
        <div>
            <h3>${postData.title}</h3>
            <p>${postData.description}</p>
            ${postData.imageBase64 ? `<img src="${postData.imageBase64}" alt="Post Image" class="post-image" />` : ""}
            <button onclick="editPost('${postId}', '${postData.title}', '${postData.description}', '${postData.imageBase64}')">Edit</button>
            <button onclick="deletePost('${postId}')">Delete</button>
        </div>
    `;
    postsContainer.appendChild(postDiv);
}

// Convert image to Base64
function getBase64Image(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
        reader.readAsDataURL(file);
    });
}

// Add new post
newPostForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const user = auth.currentUser;
    if (!user) {
        alert("No user is logged in!");
        return;
    }

    const title = postTitle.value.trim();
    const description = postDescription.value.trim();
    const imageFile = postImageInput.files[0];

    let imageBase64 = null;
    if (imageFile) {
        try {
            imageBase64 = await getBase64Image(imageFile);
        } catch (error) {
            console.error("Error reading image file:", error);
            alert("Failed to process image. Please try again.");
            return;
        }
    }

    if (title && description) {
        try {
            await db.collection("posts").add({
                userId: user.uid,
                title,
                description,
                imageBase64,
                createdAt: new Date(),
            });
            alert("Post added successfully!");
            postTitle.value = "";
            postDescription.value = "";
            postImageInput.value = "";
            loadUserPosts(user.uid);
        } catch (error) {
            console.error("Error adding post:", error);
            alert("Failed to add post. Please try again.");
        }
    } else {
        alert("Please fill in all fields!");
    }
});

// Edit post
async function editPost(postId, currentTitle, currentDescription, currentImageBase64) {
    const newTitle = prompt("Edit title:", currentTitle);
    const newDescription = prompt("Edit description:", currentDescription);

    let newImageBase64 = currentImageBase64;
    if (postImageInput.files[0]) {
        try {
            newImageBase64 = await getBase64Image(postImageInput.files[0]);
        } catch (error) {
            console.error("Error reading new image file:", error);
            alert("Failed to process new image. Please try again.");
            return;
        }
    }

    if (newTitle && newDescription) {
        try {
            await db.collection("posts").doc(postId).update({
                title: newTitle,
                description: newDescription,
                imageBase64: newImageBase64,
                updatedAt: new Date(),
            });
            alert("Post updated successfully!");
            loadUserPosts(auth.currentUser.uid);
        } catch (error) {
            console.error("Error updating post:", error);
            alert("Failed to update post. Please try again.");
        }
    } else {
        alert("No changes made!");
    }
}

// Delete post
async function deletePost(postId) {
    if (confirm("Are you sure you want to delete this post?")) {
        try {
            await db.collection("posts").doc(postId).delete();
            alert("Post deleted successfully!");
            loadUserPosts(auth.currentUser.uid);
        } catch (error) {
            console.error("Error deleting post:", error);
            alert("Failed to delete post. Please try again.");
        }
    }
}