document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.getElementById("toggleButton");
    const displayPosts = document.getElementById("displayPosts");
    const addPost = document.getElementById("addPost");
  
    const editButton = document.getElementById("editButton");
    const profileForm = document.getElementById("profileForm");
    const viewMode = document.getElementById("viewMode");
  
    // Toggle Post Section
    toggleButton.addEventListener("click", () => {
      const isAddingPost = addPost.classList.contains("hidden");
      addPost.classList.toggle("hidden", !isAddingPost);
      displayPosts.classList.toggle("hidden", isAddingPost);
      toggleButton.textContent = isAddingPost ? "View Posts" : "Add Post";
    });
  
    // Toggle Profile Edit
    editButton.addEventListener("click", () => {
      const isEditing = profileForm.classList.contains("hidden");
      profileForm.classList.toggle("hidden", !isEditing);
      viewMode.classList.toggle("hidden", isEditing);
    });
  });
  