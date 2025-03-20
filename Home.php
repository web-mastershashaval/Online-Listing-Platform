<?php 
include_once("./conn.php");  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home - Online Finder Platform</title>
    <script src="Home.js" type="module"></script>
    <link rel="stylesheet" href="Home.css" />
  </head>
  <body>
    <header>
      <h1><a href="Home.html">Home Repair Finder</a></h1>
      <nav>
        <ul>
          <li><a href="./Home.php">Home</a></li>
          <li><a href="./About.php">About</a></li>
          <li><a href="./Contact.php">Contact</a></li>
          <li><a href="./login.php" id="login">Login</a></li>
          <li><a href="./register.php" id="register">Register</a></li>
        </ul>
      </nav>
    </header>

     <!-- Hero Section -->
  <section id="home" class="hero">
    <h2>Find the Best Home Repair Experts</h2>
    <p>
      Connecting you with top professionals for all your home repair and fixture needs.
    </p>
    <section class="search">
      <form id="search-form" class="search-form">
        <input
          type="text"
          id="services"
          name="services"
          placeholder="What service do you need?"
          required
        />
        <input
          type="text"
          id="location"
          name="location"
          placeholder="Enter your location:"
          required
        />
        <button type="submit">Search</button>
      </form>
    </section>
    <div id="loading-indicator" class="loading" style="display: none">
      Loading...
    </div>
    <div id="search-results" class="search-results">
      <!-- Users will be displayed here dynamically -->
    </div>
  </section>


    

    <section class="services">
      <h2>Services</h2>
      <div class="services-container">
        <div class="service">
          <img src="images/Carpenter.jpg" alt="Plumber" />
          <h3>Plumber</h3>
          <p>Get the best plumbers near you</p>
        </div>
        <div class="service">
          <img src="images/Electrician.jpg" alt="Electrician" />
          <h3>Electrician</h3>
          <p>Get the best electricians near you</p>
        </div>
        <div class="service">
          <img src="images/Plumber.jpg" alt="Carpenter" />
          <h3>Carpenter</h3>
          <p>Get the best carpenters near you</p>
        </div>
        <div class="service">
          <img src="images/Painter.jpg" alt="Painter" />
          <h3>Painter</h3>
          <p>Get the best painters near you</p>
        </div>
      </div>
    </section>

    <section class="posts">
      <h1>Posts</h1>
      <!-- Display Posts -->
      <div id="displayPosts" class="toggle-section">
        <div id="postsContainer" class="posts-container">
          <!-- Posts will be displayed here -->
        </div>
      </div>
    </section>

    <section class="review">
      <h1>Reviews</h1>
      <p>
        The reviews below are from registered users you can also add review by
        registering and navigate to About page then you will see a input field
        to enter your review then click add review and you review will be saved
      </p>
      <div id="reviews-container" class="review-containers"></div>
    </section>
    <footer>
      <p>&copy; 2024 HomeRepair</p>
    </footer>
  </body>
</html>
