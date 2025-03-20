<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/About.css" />
    <script src="style/About.js" type="module" defer></script>
    <title>About</title>
</head>
<body>
  <header>
    <h1><a href="Home.html">HOME-REPAIR</a></h1>
    <nav>
      <ul>
        <li><a href="/Home.php">Home</a></li>
        <li><a href="./About.php">About</a></li>
        <li><a href="./Contact.php">Contact</a></li>
        <li><a href="./login.php">Login</a></li>
        <li><a href="./register.php">Register</a></li>
        <a href="/Profile/profile.php" class="profile-button">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQuANgsTrQGzCHaJdWoSJUPJreo6ODSmK_Eag&s" alt="Profile Image" 
               style="
                  background-color: red; 
                  border-radius: 50%; 
                  height: 40px; 
                  width: 40px; 
                  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3); 
                  object-fit: cover; 
                  border: 2px solid white;
               ">
      </a>
      </ul>
    </nav>
  </header>

    <section class="hero">
        <h1>
            The main goal of our platform HomeRepair is to help our customers find the best
            home repair professionals or companies for top-notch services.
        </h1>
        <p>
            Currently serving local communities, we plan to expand regionally within 2 years.
        </p>
    </section>

    <section class="review">
        <h1>Reviews</h1>
        <p>You can leave your review about your experience on the platform below. </p>
        <div class="entering-review">
          <textarea id="new-review" placeholder="Write your review here..."></textarea>
          <button id="add-review-btn">Add Review</button>
        </div>
        <div id="reviews-container" class="review-containers">

        </div>
    </section>

    <script src="About.js" type="module" defer></script>

</body>
</html>
