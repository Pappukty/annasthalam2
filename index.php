<?php

include_once './app/class/databaseConn.php';
include_once './app/class/fileUploader.php';

$DatabaseCo = new DatabaseConn();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Annasthalam</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="./image/logo.jpg" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />

  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

  <!-- FancyBox CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <style>
    .justify-text {
      text-align: justify;
    }

    .hero {
      background: url("banner.jpg") no-repeat center center/cover;
      height: 100vh;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .meal-card img {
      height: 320px;
      object-fit: cover;
    }

    .meal-card {
      height: 100%;
    }
  </style>
  <style>
    .hero {
      background: url("./image/banner-1.JPG") no-repeat center center/cover;
      height: 90vh;
      /* Full viewport height */
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
    }

    /* Optional: Add a semi-transparent overlay for better text visibility */
    .hero::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,
          0,
          0,
          0.5);
      /* Black overlay with 50% opacity */
      z-index: 1;
    }

    /* Ensure text stays on top of the overlay */
    .hero .container {
      position: relative;
      z-index: 2;
    }

    .lead {
      font-size: 18px;
      font-weight: 500;
    }

    h1 {
      font-family: "Roboto", Sans-serif;
      font-size: 45px;
      font-weight: 700;
      line-height: 1em;
      letter-spacing: 1px;
      color: white;
    }
  </style>

  <style>
    /* General Styles */
    .meal-section {
      /* background-color: rgb(216, 212, 212); */
      padding: 50px 0;
    }

    /* Container Styles */
    .meal-container {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
    }

    .meal-card {
      max-width: 600px;
      width: 100%;
      margin: auto;
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .carousel-container {
      max-width: 600px;
      width: 100%;
      text-align: center;
      margin: auto;
    }

    .calendar {
      margin-top: 20px;
    }

    /* Carousel Image Fix */
    .carousel-inner img {
      object-fit: cover;
      height: 100%;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .meal-container {
        flex-direction: column;
      }

      .carousel-container {
        width: 100%;
        margin-top: 20px;
      }
    }
  </style>

  <style>
    .donation_container {
      display: flex;
      width: 100%;
      padding: 0;
      background-color: white;
      margin: 0;
    }

    .donation_card {
      width: 50%;
      background: #fff;
      padding: 20px;
      border-radius: 8px 0 0 8px;
      box-shadow: none;
    }

    .donation_banner {
      width: 50%;
      position: relative;
    }

    .carousel img {
      object-fit: cover;
      height: 380px;
      border-radius: 0 8px 8px 0;
    }

    .carousel-caption {
      position: absolute;
      width: 100%;
      height: 100%;
      display: flex;
      text-align: center;
      align-items: center;
      justify-content: center;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.6);
      padding: 50px 60px;
      border-radius: 5px;
    }

    .carousel-caption h4 {
      color: #fff;
      margin: 0;

      text-align: center;
      align-items: center;
      display: flex;
      justify-content: center;
      font-size: 20px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .donation_container {
        flex-direction: column;
      }

      .donation_card,
      .donation_banner {
        width: 100%;
      }

      .carousel img {
        height: 250px;
        border-radius: 8px;
      }
    }

    /* 
    #meal-options option {
      padding: 14px;
    } */
  </style>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
    }

    .calendar-container {
      max-width: 550px;
      /* margin:20px; */
      background: white;
      padding: 6px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    #monthYear {
      font-size: 19px;
    }



    .month-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px;
      background: #007bff;
      color: white;
      font-size: 16px;
      border-radius: 10px 10px 0 0;
    }

    .month-header button {
      background: transparent;
      border: none;
      color: white;
      font-size: 18px;
      cursor: pointer;
    }

    .days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      background: #ddd;
      padding: 6px;
      font-weight: bold;
    }

    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      padding: 10px;
    }

    .day {
      background: white;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
      border: 1px solid #ddd;
    }

    .day:hover {
      background: #007bff;
      color: white;
    }

    .selected {
      background: #28a745 !important;
      color: white !important;
    }

    @media (max-width: 600px) {
      .calendar-container {
        width: 95%;
      }

      .day {
        padding: 15px;
        font-size: 14px;
      }
    }

    .today {
      border: 2px solid blue;
      /* Customize the color */
      /* border-radius: 50%; */
      /* font-weight: bold; */
      /* background-color: transparent !important; */
      color: black;
    }

    .today .selected {
      background-color: #28a745 !important;
      color: white !important;
      border: 2px solid #007bff !important;
    }

    #meal-options option {
      padding: 19px 30px !important;
    }
  </style>


  <!-- charousal  -->
  <style>
    .carousel-gallery {
      margin-top: 50px;
      overflow: hidden;
    }

    .swiper-container {
      width: 100%;
      overflow: hidden;
    }

    .swiper-wrapper {
      display: flex;
      transition: transform 0.3s ease;
    }

    .swiper-slide {
      position: relative;
      width: 100%;
      flex: 0 0 auto;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .image {
      width: 100%;
      height: 250px;
      background-size: cover;
      background-position: center;
      transition: transform 0.3s ease;
    }

    .swiper-slide:hover .image {
      transform: scale(1.05);
    }

    .swiper-pagination {
      position: relative;
      margin-top: 20px;
    }
  </style>

  <style>
    .carousel-container {
      position: relative;
      max-width: 100%;
      margin: 0 auto;
      overflow: hidden;
      margin-top: -150px;
    }

    .donation-carousel {
      display: flex;
      transition: transform 0.5s ease-in-out;
      width: 100%;
      justify-content: center;
      align-items: center;
    }

    .donation-item {
      flex: 0 0 33.333%;
      /* 3 items per slide */
      padding: 10px;
      display: flex;
      justify-content: center;
      /* Center the card */
    }

    .card {
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease;
      text-align: start;
      width: 80%;
      /* Adjust size of the card */
    }

    .card img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .card-body {
      padding: 15px;
    }

    .card-title {
      font-size: 1.25em;
      margin-bottom: 10px;
    }

    .card-text {
      font-size: 1em;
      margin-bottom: 15px;
    }

    .btn {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    /* Navigation buttons */
    .prev,
    .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      font-size: 2em;
      padding: 10px;
      border: none;
      cursor: pointer;
      z-index: 10;
    }

    .prev {
      left: 10px;
    }

    .next {
      right: 10px;
    }

    /* Media Queries for responsiveness */
    @media (max-width: 1024px) {
      .donation-item {
        flex: 0 0 50%;
        /* 2 items per slide on tablets */
      }
    }

    @media (max-width: 768px) {
      .donation-item {
        flex: 0 0 100%;
        /* 1 item per slide on mobile */
      }
    }

    /* Hover effect on the cards */
    .card:hover {
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
  </style>
  <style>
    .charity-banner {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('./image/banner-3.JPG') no-repeat center center;
      background-size: cover;
      background-attachment: fixed;
      color: #fff;
      padding: 60px 20px;
      min-height: 500px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .charity-content {
      display: flex;
      flex-wrap: wrap;
      max-width: 1200px;
      width: 100%;
    }

    .charity-left,
    .charity-right {
      flex: 1;
      padding: 20px;
    }

    .charity-left {
      text-align: left;
    }

    .charity-right {
      text-align: right;
    }

    .charity-left h2 {
      font-size: 2.5rem;
      font-weight: bold;
    }

    .charity-left .btn-donate {
      background: #f39c12;
      border: none;
      padding: 12px 30px;
      font-size: 1.2rem;
      font-weight: bold;
      color: #fff;
      border-radius: 30px;
      margin-top: 20px;
    }

    .charity-left .btn-donate:hover {
      background: #e67e22;
      color: #fff;
    }

    @media (max-width: 768px) {
      .charity-content {
        flex-direction: column;
        text-align: center;
      }

      .charity-left,
      .charity-right {
        text-align: center;
      }
    }
  </style>
  <style>
    /* .day {
  display: inline-block;
  width: 40px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  margin: 2px;
  cursor: pointer;
  border-radius: 5px;
  transition: 0.3s;
} */

.today {
  border: 2px solid blue;
}

.selected {
  background-color: green;
  color: white;
}

.disabled {
  color: gray;
  pointer-events: none;
  opacity: 0.5;
}

  </style>
</head>

<body>
  <!-- Hero Section -->
  <header class="hero">
    <div class="container">
      <h1 class="display-4 fw-bold">MAKE A DIFFERENCE WITH YOUR DONATION</h1>
      <p class="lead">
        Your support today can ignite powerful change. Contribute now and become a key part of the journey towards shaping brighter futures. Every donation, big or small, has the power to make a lasting impact. Together, let’s create a world where each act of generosity leads to meaningful transformation
      </p>
      <a href="#meal-options" class="btn btn-light btn-lg mt-3">Get Started</a>
    </div>
  </header>

  <!-- Meal Options -->

  <section>
    <div class="container">
      <div class="donation_container p-4 mb-5 mt-5">
        <!-- Donation Card -->
        <div class="donation_card">
          <!-- <img src="./image/imges1.jpg" alt="" class="img-fluid"> -->
          <div class="donation_card_body">
            <h2 class="card-title text-center">MEALS DONATION</h2>

            <form action="">

              <!-- Meal Selection Calendar -->
              <div class="calendar-container">
                <label for="meal-options" class="form-label fs-5">Select For Day Donation</label>
                <div class="month-header">
                  <button id="prevMonth">❮</button>
                  <h2 id="monthYear"></h2>
                  <button id="nextMonth">❯</button>
                </div>
                <div class="days">
                  <div>Sun</div>
                  <div>Mon</div>
                  <div>Tue</div>
                  <div>Wed</div>
                  <div>Thu</div>
                  <div>Fri</div>
                  <div>Sat</div>
                </div>
                <div class="calendar" id="calendar"></div>
              </div>

              <!-- Meal Package Selection -->
              <div class="mt-3 mx-auto">
                <label for="meal-options" class="form-label fs-5 p-1">Select Meal Package</label>
                <select class="form-select w-60 p-3" id="meal-options">
                  <option value="1750" class="p-3">50 Meals - 1750</option>
                  <option value="3700" class="p-3">100 Meals - 3700</option>
                  <option value="5000" class="p-3">150 Meals - 5000</option>
                </select>
              </div>

              <!-- Donation Button -->
              <a id="donate-button" href="#" class="btn btn-primary mt-3 w-100 p-2">Donate for Meals</a>
            </form>
          </div>
        </div>

        <!-- Donation Banner with Centered Text -->
        <div class="donation_banner">
          <img src="./image/old.jpg" alt="" class="img-fluid">
          <h3 class="text-primary mt-3">
          Start Someone's Day With Nourishing Meals
          </h3>
          <p>
            Your donation helps provide fresh, healthy meals to individuals in need, offering them the nourishment they deserve. Each meal you support goes beyond just food—it’s a lifeline to those facing hunger.

            By choosing a meal plan and selecting a date, you can make a direct and lasting impact. Your generosity ensures that people have access to nutritious meals, empowering them to face each day with strength and hope.

            Every donation, no matter how big or small, plays a vital role in combating hunger and poverty. Together, we can make a real difference in the lives of those who need it most.

            When you donate, you’re not just giving food—you’re offering comfort, dignity, and the opportunity for a better future. Your support brings hope to those who are struggling, helping them rebuild their lives.




          </p>
        </div>
      </div>
    </div>

  </section>
  <section>
    <div class="charity-banner">
      <div class="charity-content">
        <div class="charity-left">
          <h2>Make a Difference Today</h2>
          <a href="#" class="btn btn-donate">Donate Now</a>
        </div>
        <div class="charity-right">
          <p class="justify-text">
            Your donation has the power to change lives. Every contribution provides vital food, shelter, and support to those in need. With your help, we can make a lasting impact and assist individuals in rebuilding their lives. Every act of kindness, no matter how small, makes a difference. Join us in creating a brighter future for those less fortunate. Together, we can offer hope and help those who need it most.
          </p>



        </div>
      </div>
    </div>

    <div class="carousel-container">
      <h2 class="text-center" style="color: #fff;">CAMPAIGNS</h2>
      <div class="donation-carousel">
      <?php
      // Set the number of records per page
      $records_per_page = 5;

      // Get the current page from the URL, default to page 1 if not set
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $page = max($page, 1); // Ensure the page is at least 1

      // Calculate the OFFSET for SQL query
      $offset = ($page - 1) * $records_per_page;

      // Fetch total number of records
      $total_result = mysqli_query($DatabaseCo->dbLink, "SELECT COUNT(*) AS total FROM event");
      $total_row = mysqli_fetch_assoc($total_result);
      $total_records = $total_row['total'];

      // Calculate total pages
      $total_pages = ceil($total_records / $records_per_page);

      // Fetch paginated results
      $select = "SELECT * FROM event LIMIT $offset, $records_per_page";
      $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

      // Check if records are available
      if (mysqli_num_rows($SQL_STATEMENT) > 0) {
        while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {
          $photos = $Row['photos'];
          $raised = $Row['amount'];
          $goal = $Row['goal_amount'];
          
          // Avoid division by zero
          $percentage = ($goal > 0) ? ($raised / $goal) * 100 : 0;
          
          // Ensure percentage does not exceed 100%
          $percentage = min($percentage, 100);
          
      ?>
        <div class="donation-item">
          <div class="card">
            <img src="app/uploads/events/<?php echo $photos; ?>" alt="Donation 3" class="img-fluid">
            <div class="card-body">
              <h5 class="card-title"><?php echo $Row['title']; ?></h5>
              <p><strong>Raised:</strong> ₹<?php echo number_format($raised); ?></p>
              <p><strong>Goal:</strong> ₹<?php echo number_format($goal); ?></p>
              <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentage; ?>%;"  data-percentage="<?php echo $percentage; ?>"></div>
              </div>
              <p class="card-text mt-2"><?php echo $Row['description']; ?></p>
              <a href="#" class="btn btn-primary">Donate Now</a>
            </div>
          </div>
        </div>
        <?php
    
  }
} else {
  
?>



<?php
echo "";
}

?>
       
      </div>
      <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
      <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </div>


  </section>
  <section>

    <div class="container">
      <!-- <div class="title text-center my-5">
            <h1 class="display-4 text-danger">Responsive Carousel Gallery</h1>
        </div> -->

      <div class="carousel-gallery">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            <!-- Example Image Slides -->
            <?php
            // Fetch all data from temples table with limit and offset
            $select = "SELECT * FROM `gallery` ORDER BY index_id DESC";
            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

            // Check if any rows are returned
            if (mysqli_num_rows($SQL_STATEMENT) > 0) {
                while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {
                    $gallery_image = $Row['gallery_image'];
                    // $title = $Row['title'];
            ?>
            <div class="swiper-slide">
              <a href="./app/uploads/gallery/<?php echo $gallery_image; ?>" data-fancybox="gallery">
                <div class="image" style="background-image: url('./app/uploads/gallery/<?php echo $gallery_image; ?>');"></div>
              </a>
            </div>
                      <!-- End listing card -->
                      <?php
                }
            } else {
                echo "<p class='text-center'>No temples found.</p>";
            }
            ?>
         
          </div>

          <!-- Pagination -->
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Donation Page. All rights reserved.</p>
  </footer>


  <script>
const calendar = document.getElementById("calendar");
const monthYear = document.getElementById("monthYear");
const prevMonth = document.getElementById("prevMonth");
const nextMonth = document.getElementById("nextMonth");
const mealOptions = document.getElementById("meal-options");
const donateButton = document.getElementById("donate-button");

let currentDate = new Date(); // Track the current displayed month
let selectedDates = new Set(); // Store selected dates
let selectedMealPackage = mealOptions.value; // Track selected meal package

// Function to format date as YYYY-MM-DD
function formatDate(date) {
  return date.toISOString().split("T")[0]; // Standardized format
}

// Function to render the calendar dynamically (NO PAGE RELOAD)
function renderCalendar() {
  calendar.innerHTML = ""; // Clear previous calendar
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  
  monthYear.textContent = new Intl.DateTimeFormat("en-US", {
    month: "long",
    year: "numeric"
  }).format(currentDate);

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  const today = new Date();
  today.setHours(0, 0, 0, 0); // Reset time to avoid timezone issues

  // Add empty divs for padding (days from the previous month)
  for (let i = 0; i < firstDay; i++) {
    const emptyDiv = document.createElement("div");
    calendar.appendChild(emptyDiv);
  }

  // Loop through the days of the month
  for (let day = 1; day <= daysInMonth; day++) {
    const dayDiv = document.createElement("div");
    dayDiv.classList.add("day");
    dayDiv.textContent = day;

    const dayDate = new Date(year, month, day);
    dayDate.setHours(0, 0, 0, 0);
    const dateKey = formatDate(dayDate);

    // Highlight today's date
    if (dateKey === formatDate(today)) {
      dayDiv.classList.add("today");
    }

    // Disable past dates
    if (dayDate < today) {
      dayDiv.classList.add("disabled");
    } else {
      // Click to select/deselect dates
      dayDiv.onclick = () => {
        if (selectedDates.has(dateKey)) {
          selectedDates.delete(dateKey);
          dayDiv.classList.remove("selected");
        } else {
          selectedDates.add(dateKey);
          dayDiv.classList.add("selected");
        }
      };
    }

    // Highlight previously selected dates
    if (selectedDates.has(dateKey)) {
      dayDiv.classList.add("selected");
    }

    calendar.appendChild(dayDiv);
  }
}

// Handle month navigation WITHOUT RELOAD
prevMonth.addEventListener("click", (event) => {
  event.preventDefault(); // Prevent page reload
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar();
});

nextMonth.addEventListener("click", (event) => {
  event.preventDefault(); // Prevent page reload
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar();
});

// Update meal selection
mealOptions.onchange = () => {
  selectedMealPackage = mealOptions.value;
};

// Handle donation button click
donateButton.onclick = (event) => {
  event.preventDefault();
  if (selectedDates.size > 0) {
    const formattedDates = Array.from(selectedDates).join(",");
    donateButton.href = `donation.php?meal=${selectedMealPackage}&dates=${formattedDates}`;
    window.location.href = donateButton.href;
  } else {

 
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: 'fadeIn', // A valid animation method
                        hideMethod: 'fadeOut',
                        timeOut: 5000, // Duration in milliseconds
                    };

                    toastr.error('Please select at least one future date for your donation.');
          
    alert("Please select at least one future date for your donation.");
  }
};

// Initial render when page loads
document.addEventListener("DOMContentLoaded", renderCalendar);


  </script>
  <script>
    let currentIndex = 0;
    const items = document.querySelectorAll('.donation-item');
    const totalItems = items.length;

    function moveSlide(direction) {
      currentIndex += direction;
      if (currentIndex < 0) currentIndex = totalItems - 1; // Loop back to the last slide
      if (currentIndex >= totalItems) currentIndex = 0; // Loop back to the first slide
      updateCarousel();
    }

    function updateCarousel() {
      const newTransformValue = -(currentIndex * 100 / totalItems) + '%';
      document.querySelector('.donation-carousel').style.transform = `translateX(${newTransformValue})`;
    }

    // Auto loop the carousel every 3 seconds (removed)
  </script>

  <!-- charousal  -->

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- jQuery and FancyBox JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
  <script>
    $(document).ready(function() {
      var swiper = new Swiper('.swiper-container', {
        effect: 'slide',
        speed: 900,
        slidesPerView: 7, // 7 images visible at once
        spaceBetween: 10, // Adjust space between slides
        loop: true, // Loop enabled
        autoplay: {
          delay: 5000,
          disableOnInteraction: false
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 5
          },
          425: {
            slidesPerView: 2,
            spaceBetween: 10
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 15
          },
          1024: {
            slidesPerView: 4,
            spaceBetween: 20
          },
          1280: {
            slidesPerView: 5,
            spaceBetween: 25
          },
          1600: {
            slidesPerView: 6,
            spaceBetween: 30
          },
          1920: {
            slidesPerView: 7,
            spaceBetween: 30
          }
        }
      });

      $('[data-fancybox="gallery"]').fancybox();
    });
  </script>
<script>
    // Get all progress bars and update dynamically if needed
    document.addEventListener("DOMContentLoaded", function () {
        let progressBars = document.querySelectorAll(".progress-bar");
        progressBars.forEach(bar => {
            let percentage = bar.getAttribute("data-percentage"); // Fetch from PHP
            bar.style.width = percentage + "%"; // Set width dynamically
        });
    });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>