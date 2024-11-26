 
<?php
// Include database connection
require_once 'config/db.php';

// Fetch the school content from the database
$query = "SELECT * FROM school_content ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$content = mysqli_fetch_assoc($result);

// Fetch All Uploaded Content
$stmt = $db->query("SELECT * FROM index_manage ORDER BY created_at DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Notice
$stmt1 = $db->query("SELECT * FROM notice_manage ORDER BY created_at DESC");
$item1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Student Result Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!--script src="../result-management-app/index.js"></!--script-->

</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="profile">
            <a href="../result-management-app/index.php">
                <img src="../result-management-app/assets/images/logo.png" alt="Default Profile" class="profile-photo">
            </a>
        </div>
        <div class="title">
            <p> RET H S School</p>
        </div>
       
        <div class="nav">
        <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        <ul id="mobile-menu" class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#Events">Events</a></li>
                <li><a href="#notice">Notice Board</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div> 
        
    </div>
    
    <div class="wcs"><h1>Welcome to Our School</h1></div>

    <!-- School Image Section -->
        
    <!-- Event Section -->
<div class="event" id="events">
    <h2>School Events, Infrastructure, Buildings, and Notices</h2>
    <hr>
    <br>
    <div class="slideshow-container">
        <?php foreach ($items as $item): ?>
            <div class="slide">
                <img src="<?= 'uploads/' . htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <div class="text-overlay">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p>Posted on: <?= $item['created_at'] ?></p>
                </div>
             </div>
        <?php endforeach; ?>

        <!-- Navigation Arrows -->
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
    </div>
</div>

<!-- Notice Section -->
<div class="notice-board" id="notice_board">
    <div class="notice">
        <h2>Notice Board</h2>
        <hr>
    </div>
    <div class="notice-container">
        <div class="notice-slider">
            <?php foreach ($item1 as $item): ?>
                <div class="notice-item">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p class="notice-content">
                        <?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...
                    </p>
                    <button class="read-more">Read More</button>
                    <p class="full-content" style="display: none;">
                        <?= htmlspecialchars($item['description']) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
    <!-- About School Section -->
        <section id="about">
            <h2>About Our School</h2>
            <hr>
            <div class="about-item">
                <p>
                    <?php echo $content ? nl2br($content['about_school']) : "Information about the school is not available."; ?>
                </p>
            </div>
        </section>

     <!-- Footer Section -->
        <footer>
            <p>&copy; 2024 Our School. All Rights Reserved.</p>
        </footer>
        <script>
            // Slideshow
let slideIndex = 0;
showSlides();

function showSlides() {
    let slides = document.querySelectorAll(".slide");
    slides.forEach(slide => (slide.style.display = "none"));
    slideIndex++;
    if (slideIndex > slides.length) slideIndex = 1;
    slides[slideIndex - 1].style.display = "block";
    setTimeout(showSlides, 3000); // Change image every 3 seconds
}

function changeSlide(n) {
    slideIndex += n;
    let slides = document.querySelectorAll(".slide");
    if (slideIndex > slides.length) slideIndex = 1;
    if (slideIndex < 1) slideIndex = slides.length;
    slides.forEach(slide => (slide.style.display = "none"));
    slides[slideIndex - 1].style.display = "block";
}

// Read More Toggle
document.querySelectorAll(".read-more").forEach((button) => {
    button.addEventListener("click", function () {
        const fullContent = this.nextElementSibling;
        if (fullContent.style.display === "none" || !fullContent.style.display) {
            fullContent.style.display = "block";
            this.textContent = "Read Less";
        } else {
            fullContent.style.display = "none";
            this.textContent = "Read More";
        }
    });
});

// Dropdown Menu for Smaller Screens
function toggleMenu() {
           // JavaScript
            const menuToggle = document.querySelector('.menu-toggle');
            const navLinks = document.querySelector('.nav-links');
            const links = document.querySelectorAll('.nav-links a');

            // Toggle the navigation menu
            menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
            });

            // Hide the navigation menu after clicking a link
            links.forEach((link) => {
            link.addEventListener('click', () => {
            navLinks.classList.remove('show');
            });
         });
        }

/* notice*/

document.addEventListener("DOMContentLoaded", function() {
    const noticeSlider = document.querySelector('.notice-slider');

    // Function to pause the scrolling animation
    function pauseScroll() {
        noticeSlider.style.animationPlayState = 'paused'; // Pauses the scroll
    }

    // Function to resume the scrolling animation
    function resumeScroll() {
        noticeSlider.style.animationPlayState = 'running'; // Resumes the scroll
    }

    // Select all notice items
    const noticeItems = document.querySelectorAll('.notice-item');

    // Add event listeners to each notice item
    noticeItems.forEach(item => {
        item.addEventListener('mouseenter', pauseScroll); // Pause scroll on hover
        item.addEventListener('mouseleave', resumeScroll); // Resume scroll when mouse leaves
    });
});

  </script>
   

</body>
</html>
