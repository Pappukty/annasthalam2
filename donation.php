<?php

require 'razorpay/Razorpay.php';

use Razorpay\Api\Api;



$keyId = "rzp_test_geqUM0Pl34yBo6";
$keySecret = "ar3OtUJd6ZRwDCvy5vWkcLI0";

$api = new Api($keyId, $keySecret);

$orderData = [
    'receipt'         => rand(1000, 9999),
    'amount'          => 100 * 100, // Amount in paise (100 INR)
    'currency'        => 'INR',
    'payment_capture' => 1
];


// Fetch the data from the query string using $_GET
$meal = isset($_GET['meal']) ? $_GET['meal'] : 'Not specified';
$date = isset($_GET['dates']) ? $_GET['dates'] : 'Not specified';

// Display the values
// echo "Meal Package: " . $meal . "<br>";
// echo "Donation Date: " . $date;
?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Annasthalam</title>
  <!-- Include AOS Library (For Animations) -->

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="./image/logo.jpg" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <!-- toster -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hero {
      background: url('banner.jpg') no-repeat center center/cover;
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
      background: url('./image/banner-2.JPG') no-repeat center center/cover;
      background-position: center;
      /* Ensures background image stays centered */
      background-attachment: fixed;
      /* Keeps the background fixed while scrolling */
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
      background-color: rgba(0, 0, 0, 0.5);
      /* Black overlay with 50% opacity */
      z-index: 1;
    }

    /* Ensure text stays on top of the overlay */
    .hero .container {
      position: relative;
      z-index: 2;
    }

    /* Make the heading and paragraph more responsive */
    @media (max-width: 767px) {
      .hero {
        background-size: cover;
        /* Ensure image covers smaller screens */
      }

      .hero h1 {
        font-size: 2.5rem;
        /* Smaller font size on mobile */
      }

      .hero p {
        font-size: 1rem;
        /* Adjust paragraph text size */
      }

      .hero .btn {
        font-size: 1rem;
        /* Slightly smaller button */
      }
    }

    @media (max-width: 991px) {
      .hero {
        background-size: cover;
        /* Ensures it covers the tablet screen without distortion */
      }

      .hero h1 {
        font-size: 3rem;
        /* Adjust heading size on tablets */
      }

      .hero p {
        font-size: 1.1rem;
        /* Adjust paragraph text size */
      }
    }

    .lead {
      font-size: 18px;
      font-weight: 500;
    }

    h2 {
      font-family: "Roboto", Sans-serif;
      font-size: 40px;
      font-weight: 700;
      line-height: 1em;
      letter-spacing: 1px;
      color: #000000;
    }
  </style>
  <style>
    .form-container {
      max-width: 900px;
      margin: 2rem auto;

      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-heading {
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: bold;
    }

    .form-label {
      font-size: 17px;
      font-weight: 500;
    }
  </style>
  <style>
    .carousel-container {
      max-width: 350px;
      /* Adjust width */
      margin: auto;
      position: relative;
      overflow: hidden;
      height: 380px;
      /* Adjust based on content height */
    }

    .carousel-inner {
      display: flex;
      flex-direction: column;
      transition: transform 1s ease-in-out;
    }

    .carousel-item {
      min-height: 140px;
      /* Adjust height */
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .cause-card {
      width: 100%;
      border-radius: 10px;
      overflow: hidden;
      border: 1px solid #ddd;
      padding: 20px;
      background: #fff;
      text-align: center;
    }

    .cause-card img {
      width: 80px;
      height: 80px;
      border-radius: 5px;
    }

    .cause-info {
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: center;
    }

    .cause-text {
      font-size: 14px;
      flex-grow: 1;
    }
  </style>
  <style>
    .stats-box {
      background-color: #ddd;
      color: black;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
    }

    .mission-title {
      font-weight: bold;
      color: black;
    }

    .donate-btn {
      background-color: #17a2b8;
      border: none;
    }

    .donate-btn:hover {
      background-color: #138496;
    }

    .image-gallery img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    .image-gallery img,
    .col-6 img {
      height: 330px;
      /* Adjust to your desired height */
      object-fit: cover;
      /* Ensures the image covers the area without distorting */
    }

    .content {
      font-size: 17px;
      line-height: 32px;
    }
  </style>
  <!-- Custom CSS -->
  <style>
    .stats-box {
      background: linear-gradient(135deg, #6e8efb, #a777e3);
      color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease-in-out;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100%;
      /* Ensures equal height */
      min-height: 120px;
      /* Prevents shrinkage */
    }

    .stats-box:hover {
      transform: scale(1.05);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 5px;
    }

    p {
      font-size: 1.1rem;
      font-weight: 500;
      margin: 0;
    }
  </style>
  <style>
    .carousel-banner {
      width: 100%;
      /* height: 400px; */
      overflow: hidden;
    }

    .owl-carousel img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }

    /* Optional: Style the text if you add captions */
    .carousel-caption {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 2rem;
    }
  </style>
  <style>
    /* Success message background color */
    .toast-success {
      background-color: #28a745;
      /* Green */
    }

    /* Error message background color */
    .toast-error {
      background-color: #dc3545;
      /* Red */
    }

    /* Customize the Toastr position and other styles if needed */
    .toast {
      font-size: 16px;
      /* Set font size */
      padding: 15px;
      /* Set padding for toast message */
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
    <!-- carousal -->
 
</head>

<body>
  <header class="hero">
    <div class="container text-center my-5">
      <h1 class="display-4 fw-bold">SUPPORT THOSE IN NEED</h1>
      <p class="lead fs-5">Your contribution today is a catalyst for impact. Donate now and be part of the journey towards creating positive transformations and brighter tomorrows. Every contribution, no matter the size, makes a meaningful difference. Join us in building a future where every act of giving sparks lasting change</p>
      <a href="#donation-options" class="btn btn-primary btn-lg mt-3">Donate Now</a>
    </div>
  </header>
  <div class="container my-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="mission-title">MAKE AN IMPACT TODAY</h2>
        <p style="text-align: justify;" class="content">
          Your support provides food, shelter, and education to those in need. Every contribution makes a difference in transforming lives.<br>
          Join our mission to create a better future for underprivileged communities.<br>
          With your generosity, we can bring hope and relief to countless individuals.<br>
          Be the reason someone smiles today. Donate now and be a part of something great.<br>
          Every meal, every blanket, every helping hand can change a life for the better.<br>
          Help us reach more people in need with your kindness and support.<br>
          We believe in the power of collective effort. Together, we can make a difference.<br>
          Providing access to education and healthcare is at the heart of our mission.<br>
          Stand with us in bringing hope and relief to those facing difficult times.<br>
          Your generosity fuels our commitment to serving humanity with love and care.<br>
          Every contribution counts and brings us closer to our goal.<br>
          Together, we can make the world a better place for those in need.
        </p>


        <!-- Stats Section -->
        <div class="row g-3 my-3 mt-3">
  <div class="col-6 col-md-3 d-flex">
    <div class="stats-box w-100 h-100 text-center">
      <h3 class="stat-number" data-target="135">0</h3>
      <p>Meals Donated</p>
    </div>
  </div>
  <div class="col-6 col-md-3 d-flex">
    <div class="stats-box w-100 h-100 text-center">
      <h3 class="stat-number" data-target="19">0</h3>
      <p>Supported Shelters</p>
    </div>
  </div>
  <div class="col-6 col-md-3 d-flex">
    <div class="stats-box w-100 h-100 text-center">
      <h3 class="stat-number" data-target="90">0</h3>
      <p>Helping Hands</p>  <!-- Changed from Volunteers -->
    </div>
  </div>
  <div class="col-6 col-md-3 d-flex">
    <div class="stats-box w-100 h-100 text-center">
      <h3 class="stat-number" data-target="30">0</h3>
      <p>Successful Campaigns</p>
    </div>
  </div>
</div>



        <!-- <button class="btn btn-primary donate-btn mt-3 py-3 px-4" >Donate Now</button> -->
      </div>
      <div class="col-md-6">
        <div class="row g-2">
          <div class="col-12 image-gallery ">
            <img src="./image/imges25.jpg" class="img-fluid rounded" alt="Helping hands">
          </div>
          <div class="col-6">
            <img src="./image/donation-img.JPG" class="img-fluid rounded" alt="Old man reading">
          </div>
          <div class="col-6">
            <img src="./image/girl.jpg" class="img-fluid rounded" alt="Man sitting">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">

    <div class="row">
      <div class="col-12 col-md-8">
        <div class="form-container shadow-lg p-3 mb-5 bg-body rounded" id="donation-options">
          <h2 class="form-heading">DONATE FOR A SPECIAL DAY</h2>
          <form action="donation_process.php" id="donationForm" method="POST">
            <div class="row g-3">
              <!-- Donor Name -->
              <div class="col-12 col-md-6">
                <label for="donorName" class="form-label">Donor Name *</label>
                <input type="text" class="form-control py-3" id="donorName" name="name" placeholder="Donor Name" required>
              </div>
              <div class="col-12 col-md-6">
                <label for="donorEvent" class="form-label">Donor Event *</label>
                <select class="form-select p-3" id="donorEvent" name="event" required>
    <option value="">Select Event</option>
    <option value="food_drive">Food Drive 2025</option>
    <option value="winter_clothes">Winter Clothes Donation</option>
    <option value="school_supplies">Back-to-School Supplies Campaign</option>
    <option value="community_feeding">Community Feeding Program</option>
    <option value="medical_aid">Medical Aid Fundraiser</option>
</select>
              </div>

              <!-- Email Address -->
              <div class="col-12 col-md-6">
                <label for="emailAddress" class="form-label">Email Address *</label>
                <input type="email" class="form-control py-3" name="email" id="emailAddress" placeholder="Donor Email" required>
              </div>
              <!-- Phone Number -->
              <div class="col-12 col-md-6">
                <label for="phone" class="form-label">Phone *</label>
                <div class="input-group">
                  <span class="input-group-text py-3">🇮🇳</span>
                  <input type="tel" class="form-control py-3" id="phone" maxlength="10" name="phone" placeholder="Donor Mob No" required>
                </div>
              </div>

              <!-- Service Date -->
              <div class="col-12 col-md-6">
                <label for="serviceDate" class="form-label">Service Date</label>
                <input type="date" class="form-control py-3"id="service_date" name="service_date" value="<?php echo $date; ?>" id="serviceDate"  readonly>
              </div>
              <!-- Date of Birth -->
              <div class="col-12 col-md-6">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control py-3"  name="dob" id="dob" required >
              </div>

              <!-- Food Count -->
              <div class="col-12 col-md-6">
              <label for="meals_count" class="form-label">Meals Count</label>
<select class="form-select p-3" id="mealsCount" name="meals_count" disabled>
  <option value="50" <?php echo $meal == 1750 ? 'selected' : ''; ?>>50 Meals</option>
  <option value="100" <?php echo $meal == 3700 ? 'selected' : ''; ?>>100 Meals</option>
  <option value="150" <?php echo $meal == 5000 ? 'selected' : ''; ?>>150 Meals</option>
</select>

                <!-- <input type="number" class="form-control py-3" name="meals_count" id="foodCount" placeholder="25"> -->
              </div>
              <!-- Donation Amount -->
              <div class="col-12 col-md-6">
                <label for="donationAmount" class="form-label">Total Amount *</label>
                <input type="number" class="form-control py-3" name="total_amount" value="<?php echo $meal; ?>" id="donationAmount" placeholder="Enter Donation Amount" required readonly>
              </div>

              <!-- Additional Comments -->
              <div class="col-12">
                <label for="additionalComments" class="form-label">Additional Comments</label>
                <textarea class="form-control" id="comments" name="comments" rows="4"></textarea>
              </div>
            </div>

            <div class="mt-4 text-center">
            <button type="button" id="payWithRazorpay" class="btn btn-primary py-3 px-5">Pay with Razorpay</button>
            </div>
          </form>
        </div>
      </div>



      <div class="col-12 col-md-4">
        <div class="container mt-4" style="background-color: #F6F4F1;">
          <h4 class="text-center m-4 p-3">Recommend Causes</h4>

          <!-- Auto Vertical Carousel -->
          <div class="carousel-container">
            <div id="verticalCarousel" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges01.jpg" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong>Virtual Cake Cutting</strong><br>
                        <span>₹ 4000 / celebration</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges07.jpeg" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong>Sponsor a Birthday Cake</strong><br>
                        <span>₹ 1500 - 20 Children</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges20.webp" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong><a href="#" class="text-primary">Feed a homeless person Thaali</a></strong><br>
                        <span>₹ 60 / Person</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Slide 4 -->
                <div class="carousel-item">
                  <div class="cause-card">
                    <div class="cause-info">
                      <img src="./image/imges18.jpg" alt="Cause" class="img-fluid">
                      <div class="cause-text">
                        <strong>Feed a Homeless Person</strong><br>
                        <span>₹ 25 / Person</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="container mt-5">
            <div class="owl-carousel owl-theme carousel-banner">
              <div class="item">
                <img src="./image/poster_banner_1.png" alt="Banner 1" class="img-fluid">
              </div>
              <div class="item">
                <img src="./image/poster_banner_2.png" alt="Banner 2" class="img-fluid">
              </div>
              <div class="item">
                <img src="./image/poster_banner_3.png" alt="Banner 3" class="img-fluid">
              </div>
              <div class="item">
                <img src="./image/poster_banner_4.png" alt="Banner 4" class="img-fluid">
              </div>
              <div class="item">
                <img src="./image/poster_banner_5.png" alt="Banner 5" class="img-fluid">
              </div>
              <div class="item">
                <img src="./image/poster_banner_6.png" alt="Banner 6" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
  </div>

  <section>
    
    <div class="container">
          <!-- <div class="title text-center my-5">
              <h1 class="display-4 text-danger">Responsive Carousel Gallery</h1>
          </div> -->
          
          <div class="carousel-gallery">
              <div class="swiper-container">
                  <div class="swiper-wrapper">
                      <!-- Example Image Slides -->
                      <div class="swiper-slide">
              <a href="./image/imges01.jpg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges01.jpg');"></div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="./image/imges02.jpeg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges02.jpeg');"></div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="./image/imges05.jpeg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges05.jpeg');"></div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="./image/imges06.jpg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges06.jpg');"></div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="./image/imges10.jpg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges10.jpg');"></div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="./image/imges08.jpg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges07.jpeg');"></div>
              </a>
            </div>
            <div class="swiper-slide">
              <a href="./image/imges09.jpeg" data-fancybox="gallery">
                <div class="image" style="background-image: url('./image/imges09.jpeg');"></div>
              </a>
            </div>
                  </div>
                  
                  <!-- Pagination -->
                  <div class="swiper-pagination"></div>
              </div>
          </div>
      </div>
    </section>
  <!-- Toastr JS -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script>
    $(document).ready(function() {
      $("#donationForm").submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        // Log form data to the console before submission
        console.log("Form Data:", $(this).serialize());

        $.ajax({
          url: "donation_process.php",
          type: "POST",
          data: $(this).serialize(),
          dataType: "json", // Expecting a JSON response
          success: function(response) {
            // Handle the success or error response
            if (response.status === "success") {
              console.log("Form submitted successfully!");
              toastr.success(response.message, 'Success', {
                timeOut: 5000
              }); // Show success message
              $("#donationForm")[0].reset(); // Reset form after submission
            } else {
              console.log("Error: " + response.message);
              toastr.error(response.message, 'Error', {
                timeOut: 5000
              }); // Show error message
            }
          },
          error: function(xhr, status, error) {
            console.log("AJAX Error:", error); // Detailed error message
            console.log("Response Text:", xhr.responseText); // Log response text
            toastr.error('Something went wrong. Please try again.', 'Error', {
              timeOut: 5000
            }); // Show error message
          }
        });
      });
    });
    // toastr.success('Form submitted successfully!', 'Success', {
    //     timeOut: 5000,
    //     positionClass: 'toast-top-right',  // Change the position
    //     progressBar: true  // Show progress bar
    // });
  </script>


  <script>
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    const carouselInner = document.querySelector('.carousel-inner');

    function updateCarousel() {
      const offset = -currentIndex * 140; // Adjust based on item height
      carouselInner.style.transform = `translateY(${offset}px)`;
    }

    function autoScroll() {
      currentIndex = (currentIndex + 1) % totalItems; // Loop back to first
      updateCarousel();
    }

    // Auto-scroll every 3 seconds
    setInterval(autoScroll, 3000);
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const counters = document.querySelectorAll(".stat-number");

      const startCounter = (counter) => {
        const target = +counter.getAttribute("data-target");
        let current = 0;
        const increment = target / 100; // Adjust speed

        const updateCounter = () => {
          current += increment;
          counter.innerText = Math.ceil(current);
          if (current < target) {
            requestAnimationFrame(updateCounter);
          } else {
            counter.innerText = target; // Ensure exact target
          }
        };
        updateCounter();
      };

      // Intersection Observer to trigger animation on scroll
      const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            startCounter(entry.target);
            observer.unobserve(entry.target); // Runs only once
          }
        });
      });

      counters.forEach((counter) => observer.observe(counter));
    });
  </script>

  <script>
    $(document).ready(function() {
      $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 1
          },
          1000: {
            items: 1
          }
        }
      });
    });
  </script>
  
 <!-- Razorpay JS -->
<!-- Razorpay SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php
$razorpayKey = "rzp_test_geqUM0Pl34yBo6"; // Replace with your Razorpay key
?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.getElementById("payWithRazorpay").addEventListener("click", function () {
    var name = document.getElementById("donorName").value;
    var email = document.getElementById("emailAddress").value;
    var phone = document.getElementById("phone").value;
    var amount = document.getElementById("donationAmount").value * 100; // Convert to paise
    var event = document.getElementById("donorEvent").value;
    var serviceDate = document.getElementById("service_date").value;
    var dob = document.getElementById("dob").value;
    var mealsCount = document.getElementById("mealsCount").value;
    var comments = document.getElementById("comments").value;

    if (!name || !email || !phone || !amount) {
        alert("Please fill all required fields!");
        return;
    }

    var options = {
        "key": "<?php echo $razorpayKey; ?>",
        "amount": amount,
        "currency": "INR",
        "name": "Annasthalam",
        "description": "Donation to Charity",
        "image": "https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/razorpay-icon.png",
        "handler": function (response) {
          console.log(response);
            savePayment(response, name, email, phone, amount / 100, event, serviceDate, dob, mealsCount, comments);
        },
        "prefill": {
            "name": name,
            "email": email,
            "contact": phone
        },
        "theme": {
            "color": "#3399cc"
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
});

function savePayment(response, name, email, phone, amount, event, serviceDate, dob, mealsCount, comments) {
    var xhr = new XMLHttpRequest();
    console.log(response);
    xhr.open("POST", "donation_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert("Payment successful! Your transaction ID: " + response.razorpay_payment_id);
            location.reload();
        }
    };
    xhr.send("payment_id=" + response.razorpay_payment_id + "&name=" + name + "&email=" + email + "&phone=" + phone + "&amount=" + amount + "&event=" + event + "&service_date=" + serviceDate + "&dob=" + dob + "&meals_count=" + mealsCount + "&comments=" + comments);
}
</script>
<!-- charousal  -->

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- jQuery and FancyBox JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function () {
            var swiper = new Swiper('.swiper-container', {
                effect: 'slide',
                speed: 900,
                slidesPerView: 7,  // 7 images visible at once
                spaceBetween: 10, // Adjust space between slides
                loop: true,  // Loop enabled
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                breakpoints: {
                    320: { slidesPerView: 1, spaceBetween: 5 },
                    425: { slidesPerView: 2, spaceBetween: 10 },
                    768: { slidesPerView: 3, spaceBetween: 15 },
                    1024: { slidesPerView: 4, spaceBetween: 20 },
                    1280: { slidesPerView: 5, spaceBetween: 25 },
                    1600: { slidesPerView: 6, spaceBetween: 30 },
                    1920: { slidesPerView: 7, spaceBetween: 30 }
                }
            });
            
            $('[data-fancybox="gallery"]').fancybox();
        });
    </script>
   

    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>