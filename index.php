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
<!-- toster -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
    .hero .btn {
        font-size: 1rem;
        padding: 10px 12px;
      }
    
    /* Tablet (768px and below) */
    @media (max-width: 768px) {
      .hero {
        height: 100vh;
        background-size: cover;
        padding: 15px;
      }

      .hero h1 {
        font-size: 18px;
      }

      .hero p {
        font-size: 14px;
      }

      .hero .btn {
        font-size: 1rem;
        padding: 10px 20px;
      }
    }

    /* Mobile (480px and below) */
    @media (max-width: 480px) {
      .hero {
        height: 100vh;
        padding: 8px;
      }

      .hero h1 {
        font-size: 17px;
        margin-top: 20px !important;
      }

      .hero p {
        font-size: 13px;
        text-align: justify;
      }

      .hero .btn {
        font-size: 1rem;
        padding: 5px 12px;
      }

      .banner-title {
        font-size: 13px;
       
      }
      .lead {
      font-size: 16px;
      font-weight: 400;
    }

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
      .donation_banner p{
        font-size: 16px;
      }
    }
    @media (max-width: 480px) {

      .donation_banner h3{
        font-size: 17px;
      }
      .donation_banner p{
        font-size: 14px;
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
      margin: 0;
      padding: 0;
    }

    .calendar-container {
      max-width: 570px;
      width: 100%;
      background: white;
      padding: 6px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin: auto;
    }

    #monthYear {
      font-size: 18px;
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
      font-size: 14px;
      text-align: center;
    }

    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      padding: 10px;
    }

    .day {
      background: white;
      padding: 12px;
      text-align: center;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
      border: 1px solid #ddd;
      font-size: 16px;
    }

    .day:hover {
      background: #007bff;
      color: white;
    }

    .selected {
      background: #28a745 !important;
      color: white !important;
    }

    .today {
      border: 2px solid blue;
      color: black;
    }

    .today .selected {
      background-color: #28a745 !important;
      color: white !important;
      border: 2px solid #007bff !important;
    }

    #meal-options option {
      padding: 10px;
    }

    /* Disabled Dates */
    .disabled {
      background-color: #d5e0d2;
      color: #721c24;
      cursor: not-allowed;
      position: relative;
    }

    .disabled .closed-text {
      font-size: 12px;
      font-weight: bold;
      position: absolute;
      bottom: 5px;
      left: 50%;
      transform: translateX(-50%);
      color: #721c24;
    }
    .form-label{
  font-size: 18px;
}
    /* Responsive Fixes */
    @media (max-width: 768px) {
      .calendar-container {
        width: 100%;
      }

      .day {
        padding: 8px;
        font-size: 14px;
      }

      .month-header {
        font-size: 14px;
        padding: 6px;
      }

      .month-header button {
        font-size: 16px;
      }

      .days {
        font-size: 12px;
        padding: 4px;
      }
    }

    @media (max-width: 480px) {
      .calendar-container {
        width: 100%;
      }

      .day {
        padding: 6px;
        font-size: 12px;
      }

      .month-header {
        font-size: 12px;
        padding: 5px;
      }

      .month-header button {
        font-size: 14px;
      }
.form-label{
  font-size: 14px;
}
      .days {
        font-size: 10px;
        padding: 3px;
      }
      .disabled .closed-text{
        font-size: 8px;
      font-weight: bold;
      }
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

/* Flexbox for carousel */
.donation-carousel {
  display: flex;
  transition: transform 0.5s ease-in-out;
  width: 100%;
  justify-content: center;
  align-items: center;
}

/* Campaign Title */
.campaign {
  color: white !important;
  font-size: 24px;
}

/* Donation Item */
.donation-item {
  flex: 0 0 33.333%;
  /* 3 items per slide (Desktop) */
  padding: 10px;
  display: flex;
  justify-content: center;
}

/* Card Styling */
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
  font-size: 18px;
  margin-bottom: 10px;
}

.card-text {
  font-size: 14px;
  margin-bottom: 15px;
}

/* Buttons */
.btn {
  display: inline-block;
  background-color: #007bff;
  color: white;
  padding: 8px 16px;
  font-size: 14px;
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
  font-size: 1.5em;
  padding: 8px;
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

/* Responsive Media Queries */
@media (max-width: 1024px) {
  .donation-item {
    flex: 0 0 50%;
    /* 2 items per slide on tablets */
  }

  .campaign {
    font-size: 20px;
  }

  .card-title {
    font-size: 16px;
  }

  .card-text {
    font-size: 12px;
  }

  .btn {
    font-size: 12px;
    padding: 6px 12px;
  }
}

@media (max-width: 768px) {
  .donation-item {
    flex: 0 0 100%;
    /* 1 item per slide on mobile */
  }

  .carousel-container {
    margin-top: 20px;
    color: black !important;
  }

  .campaign {
    font-size: 18px;
    color: black !important;
  }

  .small-banner-title {
    font-size: 16px !important;
  }

  .card-title {
    font-size: 18px;
    font-weight: 600;
  }

  .card-text {
    font-size: 12px;
  }

  .btn {
    font-size: 12px;
    padding: 5px 10px;
  }
}
@media (max-width: 480px) {
  .card-title {
    font-size: 18px;
    font-weight: 600;
  }

  .campaign {
    font-size: 18px;
    color: black !important;
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

    @media (max-width: 480px) {

      .charity-left h2 {
      font-size: 18px;
      font-weight: bold;
    }
    .charity-right p {
      font-size: 14px;
      font-weight: bold;
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

  <!-- header -->
  <style>
      .top-bar {
      background: #006cbe;
      color: white;
      padding: 15px 15px;
      display: flex;
     
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    /* Links */
    .top-bar a {
      color: white;
      text-decoration: none;
      font-size: 17px;
      font-weight: 550;
      margin-right: 15px;
      display: inline-flex;
      align-items: center;
    }

    .top-bar i {
      font-size: 16px;
      color: #6EC1E4;
      margin-right: 5px;
    }

    /* Rolling Text */
    .rolling-container {
      flex-grow: 1;
      text-align: center;
      overflow: hidden;
      white-space: nowrap;
      position: relative;
      max-width: 50%;
    }

    .rolling-text {
      display: inline-block;
      animation: rollText 10s linear infinite;
      font-size: 17px;
    }

    @keyframes rollText {
      from {
        transform: translateX(100%);
      }

      to {
        transform: translateX(-100%);
      }
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
      .top-bar {
        flex-direction: column;
        text-align: center;
      }

      .rolling-container {
        max-width: 100%;
        margin-top: 5px;
      }

      .top-bar div {
        width: 100%;
        margin: 5px 0;
      }

      .top-bar a {
        display: block;
        margin: 5px 0;
        font-size: 14px;
      }
    }
      .header-logo img {
        height: 50px;
      }
      .navbar-nav .nav-item {
        padding: 0 10px;
      }
      .nav-link {
        color: black;
        font-weight: 500;
      }
      .new-badge {
        background: #00c09d;
        color: white;
        font-size: 12px;
        padding: 2px 5px;
        border-radius: 3px;
        margin-left: 5px;
      }
      .btn-member {
        font-size: 14px;
        font-weight: bold;
        line-height: 1em;
        letter-spacing: 1px;
        color: #ffffff;
        border-radius: 30px;
        box-shadow: 0px 0px 0px 0px rgb(0 0 0 / 10%);
        padding: 12px 25px 12px 25px;
        border-radius: 25px;
        background-color: transparent;
        background-image: linear-gradient(180deg, #0e336a 0%, #164996 100%);
      }
      .carousel-item {
        height: 300px;
        background: #ddd;
        color: black;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
      }
      .navbar-toggler {
        order: 1;
        border: none;
        outline: none;
      }
      .navbar-collapse {
        order: 2;
        justify-content: flex-end;
      }
      .navbar-nav {
      }
      .carousel {
        background-image: url(../image/imges17.webp);
      }
      .nav-link {
        padding: 0px 7px;
        font-size: 16px;
        font-weight: normal;
        line-height: 1em;
        letter-spacing: 1px;
        color: #000000;
      }
      /* Mobile-specific styles */
      @media (max-width: 991px) {
        .navbar-collapse {
          position: absolute;
          top: 100%;
          left: 0;
          width: 100%;
          background: black;
          z-index: 1000;
          padding: 10px 0;
        }
        .navbar-nav .nav-link {
          color: white !important;
          font-size: 24px;
        }
        .navbar-toggler-icon {
          background-image: none;
          font-size: 24px;
          color: white;
        }
      }
      .collapse {
        padding: 30px 0px;
        /* filter: brightness(100%) contrast(100%) saturate(0%) blur(0px) hue-rotate(0deg); */
      }
    </style>
    <style>
      /* Dropdown Menu */
      .dropdown-menu {
        background: rgba(0, 0, 0, 0.8); /* Semi-transparent black */
        border: none;
        padding: 10px;
        border-radius: 5px;
      }

      .dropdown-item {
        color: white;
        font-size: 16px;
        padding: 10px;
        transition: background 0.3s ease;
      }

      .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.2);
      }

      /* Navbar Transparency */
      .navbar {
        background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
        transition: background 0.3s ease-in-out;
      }

      .navbar:hover {
        background: rgba(255, 255, 255, 1); /* Full opacity on hover */
      }
    </style>

    <!-- footer -->
    
    <style>
      /* Footer Styling */
      .footer {
        background: #002147;
        color: white;
        padding: 40px 0;
      }
      .footer a {
        color: white;
        text-decoration: none;
      }
      .footer a:hover {
        text-decoration: underline;
      }
      .newsletter input {
        width: 70%;
        padding: 10px;
        border: none;
        border-radius: 5px;
      }
      .newsletter button {
        padding: 10px 20px;
        background: #00d084;
        color: white;
        border: none;
        border-radius: 5px;
      }
      .social-icons a {
        margin: 0 10px;
        font-size: 24px;
      }
      .supporters img {
        width: 100px;
        height: 100px;
        margin: 5px;
      }
      .footer-content {
        font-weight: normal;
        font-size: 14px;
        line-height: 2em;
        letter-spacing: 1px;
      }
      .list-unstyled {
        display: flex;
        min-height: 1px;
        position: relative;
        gap: 2rem;
      }
      .list-unstyled li {
        margin-top: 20px;
        margin-bottom: 20px;
      }
      .newsletter input {
        text-align: left;
        width: 100%;
        color: #A4A4A4 !important;
        background-color: #FFFFFF21;
        border: none;
        border-radius: 0px;
        padding: 10px 20px;
      }
      .newsletter button {
        background-color: #00baa3;
        border-width: 1px;
        border-color: #00baa3;
        border-radius: 30px;
        border-style: solid;
        padding: 10px 30px;
        margin-left: 30px;
        -webkit-appearance: none;
        color: #ffffff;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
      }
      .copy {
        color: #164A9A;
        font-size: inherit;
        font-weight: 700;
        line-height: inherit;
      }

      /* Responsive Styles */
      @media screen and (max-width: 1024px) {
        /* Tablet View */
        .list-unstyled {
          flex-wrap: wrap;
          gap: 1rem;
        }
        .newsletter {
          text-align: center;
        }
        .newsletter input {
          width: 80%;
        }
        .newsletter button {
          margin-left: 0;
          margin-top: 10px;
        }
        .supporters img {
          width: 80px;
          height: 80px;
        }
      }

      @media screen and (max-width: 768px) {
        /* Mobile View */
        .footer {
          padding: 30px 10px;
          text-align: center;
        }
        .list-unstyled {
          flex-direction: column;
          align-items: start;
        }
        .newsletter input {
          width: 100%;
        }
        .newsletter button {
          width: 100%;
          padding: 12px;
        }
        .social-icons a {
          font-size: 20px;
          margin: 5px;
        }
        .supporters img {
          width: 60px;
          height: 60px;
        }
        .footer-logo{
          width: 200px;
          height: 100px;
        }
        .SUPPORTERS{
         margin-top: 10px;
        }
      }
      .card-link {
      text-decoration: none;
      color: inherit;
      display: block;
      position: relative;
  }
    </style>
</head>

<body>
<?php
    // Fetch all data from cab_tour_packages table
    $select = "SELECT * FROM business_setting";

    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

    // Check if the query returns any rows
    if (mysqli_num_rows($SQL_STATEMENT) > 0) {
        while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {


            //  echo $photo;

    ?>
<div class="top-bar">
    <div>
      <a href="https://chandransyuva.com/index.php/causes/isr/">
        <i class="fa-solid fa-id-card"></i> Become Member
      </a>
      <a href="https://www.google.com/maps/place/Chandrans+Yuva+Foundation/">
        <i class="fa-solid fa-map-marker-alt"></i> Get Direction
      </a>
    </div>

    <div class="rolling-container">
      <div class="rolling-text"><span class="p-1 fs-bold" style="background-color: #002147; color:white;">update</span> We are donating food daily in our Head Office - Coimbatore </div>
    </div>

    <div>
      <a href="mailto:info@chandransyuva.com">
        <i class="fa fa-envelope"></i> <?php echo $Row['email']; ?>
      </a>
      <a href="tel:+91<?php echo $Row['phone_number']; ?>">
        <i class="fa fa-phone"></i> +91 <?php echo $Row['phone_number']; ?>
      </a>
    </div>
  </div>
  <?php
        }
    } else {
        echo "";
    }
    ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <a
        class="navbar-brand header-logo d-flex justify-content-start align-items-start ms-4"
        href="https://chandransyuva.com/index.php/causes/isr/"
      >
        <img src="./image/imges1.webp" alt="Logo" />
      </a>
      <div class="container">
        <a href="https://chandransyuva.com/index.php/causes/isr/" class="btn btn-member d-lg-none d-sm-block"
          >Become ISR Member</a
        >

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/nd-donation/causes-01/">Causes</a></li>
            <li class="nav-item">
              <a class="nav-link" href="https://chandransyuva.com/index.php/about-02/"
                >About <span class="new-badge">NEW</span></a
              >
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="yearbookDropdown"
                role="button"
                data-bs-toggle="dropdown"
              >
                Year Book
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Annual-Report-CYF-2018-2019.pdf">2017 – 2018</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Annual-Report-CYF-2018-2019.pdf">2018 – 2019</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Yuva-Annual-Report-CYF-2019-2020.pdf">2019 – 2020</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Year-Book-CYF-2020-2021.pdf">2020 – 2021</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="NewsletterDropdown"
                role="button"
                data-bs-toggle="dropdown"
              >
                Newsletter
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Year-Book-CYF-2020-2021.pdf">2021 – May</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/Cyf-newsletter-sep2021.pdf">2021 – september</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/CYF-E-news-letter22.pdf">2022 – March</a></li>
                <li><a class="dropdown-item" href="https://chandransyuva.com/wp-content/uploads/2024/01/News-Letter.pdf">2022 – Autg-Out</a></li>
              </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/blog/">Blog</a></li>
            <li class="nav-item">
              <a class="nav-link" href="https://chandransyuva.com/index.php/gallery-pages/gallery-01/"
                >Gallery <span class="new-badge">NEW</span></a
              >
            </li>
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/events/category/event/">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="https://chandransyuva.com/index.php/contact-pages/contact-02/">Contact</a></li>
          </ul>
          <a href="https://chandransyuva.com/index.php/causes/isr/" class="btn btn-member d-none d-lg-block"
            >Become ISR Member</a
          >
        </div>
      </div>
    </nav>
  <!-- Hero Section -->
  <header class="hero">
    <div class="container">
      <h1 class="display-4 fw-bold">MAKE A DIFFERENCE WITH YOUR DONATION</h1>
      <p class="lead">
        Your support today can ignite powerful change. Contribute now and become a key part of the journey towards shaping brighter futures. Every donation, big or small, has the power to make a lasting impact. Together, let’s create a world where each act of generosity leads to meaningful transformation
      </p>
      <a href="#meal-option" class="btn btn-light btn-lg mt-3">Get Started</a>
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

            <form action="" id="meal-option">

              <!-- Meal Selection Calendar -->
              <div class="calendar-container">
                <label for="meal-options" class="form-label ">Select Day Donation</label>
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
                <label for="meal-options" class="form-label  p-1">Select Meal Package</label>
                <select class="form-select w-60 p-3" id="meal-options">
                  <option value="1" class="p-3">50 Meals - 1750</option>
                  <option value="2" class="p-3">100 Meals - 3700</option>
                  <option value="3" class="p-3">150 Meals - 5000</option>
                </select>
              </div>

              <!-- Donation Button -->
              <a id="donate-button" href="#" class="btn btn-primary mt-3 w-100 p-2">Donate for Meals</a>
            </form>
          </div>
        </div>

        <!-- Donation Banner with Centered Text -->
        <div class="donation_banner">
          <img src="./image/donation2.png" alt="" class="img-fluid rounded">
          <h3 class="text-primary mt-3 fs-bold " style="font-weight: 500;">
            Start Someone's Day With Nourishing Meals
          </h3>
          <p>
            Hunger affects millions, making it difficult for individuals to focus on work, education, and daily life. Your donation provides fresh, nutritious meals, ensuring essential sustenance for those in need. Every meal goes beyond just food it offers comfort, dignity, and hope for a better future. By selecting a meal plan and date, you directly impact lives, helping families face each day with strength. No contribution is too small; every donation plays a vital role in the fight against hunger. Your support enables us to provide consistent, high-quality meals to those struggling with food insecurity. Together, we can make a meaningful difference, empowering communities and bringing positive change. With your generosity, we create a world where no one has to go hungry. Join us in building a future filled with hope, dignity, and nourishment for all.
          </p>
        </div>
      </div>
    </div>

  </section>
  <section>
    <div class="charity-banner">
      <div class="charity-content">
        <div class="charity-left">
          <h2 class="small-banner-title">Make a Difference Today</h2>
          <a href="#meal-option" class="btn btn-donate">Donate Now</a>
        </div>
        <div class="charity-right">
          <p class="justify-text">
            Your donation has the power to change lives. Every contribution provides vital food, shelter, and support to those in need. With your help, we can make a lasting impact and assist individuals in rebuilding their lives. Every act of kindness, no matter how small, makes a difference. Join us in creating a brighter future for those less fortunate. Together, we can offer hope and help those who need it most.
          </p>



        </div>
      </div>
    </div>

    <div class="carousel-container">
    <h2 class="text-center campaign">CAMPAIGNS</h2>
    <div class="donation-carousel row d-flex flex-wrap justify-content-center">
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
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 donation-item">
                    <div class="card">
                        <a href="https://chandransyuva.com">
                            <img src="app/uploads/events/<?php echo $photos; ?>" alt="Donation Image" class="img-fluid">
                        </a>
                        <div class="card-body">
                            <a href="https://chandransyuva.com" class="card-link">
                                <h6 class="card-title" style="font-size: 20px;"><?php echo $Row['title']; ?></h6>
                            </a>
                            <p><strong>Raised:</strong> ₹<?php echo number_format($raised); ?></p>
                            <p><strong>Goal:</strong> ₹<?php echo number_format($goal); ?></p>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $percentage; ?>%;" data-percentage="<?php echo $percentage; ?>"></div>
                            </div>
                            <p class="card-text mt-2"><?php echo $Row['description']; ?></p>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "";
        }
        ?>
    </div>
    <button class="prev btn btn-secondary" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next btn btn-secondary" onclick="moveSlide(1)">&#10095;</button>
</div>



  </section>
  <section>

    <div class="container">
      <!-- <div class="title text-center my-5">
            <h1 class="display-4 text-danger">Responsive Carousel Gallery</h1>
        </div> -->

      <div class="carousel-gallery mb-3">
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

  <footer class="footer">
      <div class="container">
        <div class="row ">
          <div class="col-md-6 text-start">
            <img
              src="./image/footer-logo.png"
              alt="Logo"
     
              class="footer-logo"
              width="290px";
              height="100px";
            />
           
          </div>
     
          <div class="col-md-6 text-start">
    <h5 class="SUPPORTERS">OUR SUPPORTERS</h5>
    <div class="supporters d-flex flex-wrap justify-content-center">
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com1.png" class="img-fluid" alt="Karpagam University">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com2.png" class="img-fluid" alt="Sri Krishna Institutions">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com3.png" class="img-fluid" alt="Sankara Institutions">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com4.png" class="img-fluid" alt="SSVM World School">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com5.png" class="img-fluid" alt="Charity Foundation">
        </div>
        <div class="col-4 col-md-4 col-lg-2 text-center">
            <img src="./image/img-com6.png" class="img-fluid" alt="Rathinam Group">
        </div>
    </div>
</div>

          <div class="col-md-6 text-start">
           
            <p class="footer-content">
              Charity is not just an action; it's a shared commitment to
              uplifting those in need, creating a symphony of hope that
              resonates across every corner of our global community.
            </p>
            <ul class="list-unstyled">
              <div class="footer-list">
                  <li>🔵 Choose your favourite cause</li>
                  <li>🔴 Be a part of our cause</li>
              </div>
              <div class="footer-list">
                  <li>🟡 Register to our website!</li>
                  <li>🟢 Stay tuned about cause</li>
              </div>
          </ul>
          
          </div>
          <div class="col-md-6 text-start mt-4">
    <h5>NEWSLETTER</h5>
    <div class="newsletter d-flex flex-column flex-md-row align-items-center">
        <input type="email" placeholder="Email" class="form-control me-md-2" />
        <button class="send">SEND</button>
    </div>
</div>


        </div>
        
     
      </div>
     
    </footer>
   
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center py-3" style="background-color: #E7E7E7;
    ;">
      
        <div class="ms-5">
          <a href="https://limitless360.org/" style="text-decoration: none;">
          <p class="mb-0 copy" style="">
              Chandrans Yuva Foundation &copy; 2017 - 2024. Powered by Limitless 360
          </p>
          </a>
         
      </div>
      <?php
    // Fetch all data from cab_tour_packages table
    $select = "SELECT * FROM business_setting";

    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);

    // Check if the query returns any rows
    if (mysqli_num_rows($SQL_STATEMENT) > 0) {
        while ($Row = mysqli_fetch_assoc($SQL_STATEMENT)) {


            //  echo $photo;

    ?>
      <div class="social-icons mt-2 mt-md-0 me-5">
          <a href="<?php echo $Row['fb_link']; ?>" class="me-3"><i class="fab fa-facebook"></i></a>
          <a href="<?php echo $Row['instagram']; ?>"><i class="fab fa-instagram"></i></a>
      </div>
   
      <?php
        }
    } else {
        echo "";
    }
    ?>
  </div>
  
  <?php
  include_once './app/class/databaseConn.php';
  include_once './app/class/fileUploader.php';

  // Create an instance of the database connection
  $DatabaseCo = new DatabaseConn();

  // Prepare the SQL query to get the donation count for each date
  $stmt = $DatabaseCo->dbLink->prepare("SELECT service_date, COUNT(*) as donationCount FROM donation GROUP BY service_date");
  $stmt->execute();
  $stmt->bind_result($serviceDate, $donationCount);

  // Create an array to store the dates and donation counts
  $donationData = [];
  while ($stmt->fetch()) {
    if ($donationCount == 3) { // Only store dates with exactly 3 donations
      $formattedDate = date('Y-m-d', strtotime($serviceDate));

      $donationData[] = [
        'date' => $formattedDate,
        'count' => $donationCount
      ];
    }
  }

  $stmt->close();

  // Pass the donation data to JavaScript
  echo "<script>";
  echo "const donationData = " . json_encode($donationData) . ";";
  echo "console.log(donationData);"; // Output the data in JavaScript console
  echo "</script>";
  ?>





  <script>
    const calendar = document.getElementById("calendar");
    const monthYear = document.getElementById("monthYear");
    const prevMonth = document.getElementById("prevMonth");
    const nextMonth = document.getElementById("nextMonth");
    const mealOptions = document.getElementById("meal-options");
    const donateButton = document.getElementById("donate-button");

    const formattedDate = <?php echo json_encode($donationData); ?>;
    let dates = [];
    donationData.forEach(item => {
      console.log(`On ${item.date}, you can make a donation today. Current donations: ${item.count}`);
      dates.push(item.date);
    });
    console.log(dates);
    let currentDate = new Date();
    let selectedDates = new Set();
    let selectedMealPackage = mealOptions.value;

    function formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    }

    function renderCalendar() {
      calendar.innerHTML = "";
      const year = currentDate.getFullYear();
      const month = currentDate.getMonth();

      monthYear.textContent = new Intl.DateTimeFormat("en-US", {
        month: "long",
        year: "numeric"
      }).format(currentDate);

      const firstDay = new Date(year, month, 1).getDay();
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      const today = new Date();
      today.setHours(0, 0, 0, 0);

      for (let i = 0; i < firstDay; i++) {
        const emptyDiv = document.createElement("div");
        calendar.appendChild(emptyDiv);
      }

      for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement("div");
        dayDiv.classList.add("day");
        dayDiv.textContent = day;

        const dayDate = new Date(year, month, day);
        dayDate.setHours(0, 0, 0, 0);
        const dateKey = formatDate(dayDate);

        dates.forEach(date => {
          if (dateKey === date) {
            dayDiv.classList.add("disabled");
            dayDiv.title = "Donations Closed for this Date";

            const infoText = document.createElement("span");
            infoText.textContent = "Closed";
            infoText.classList.add("closed-text");
            dayDiv.appendChild(infoText);
          }
        });

        if (dayDate < today) {
          dayDiv.classList.add("disabled");
        } else {
          fetch(`donation_check.php?date=${dateKey}&meal=${selectedMealPackage}`)
            .then(response => response.text())
            .then(data => {
              if (data === "completed") {
                dayDiv.classList.add("completed");
              } else {
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
            })
            .catch(error => console.error('Error checking donation availability:', error));
        }

        if (selectedDates.has(dateKey)) {
          dayDiv.classList.add("selected");
        }

        // ✅ Add special border for today's date
        if (dayDate.toDateString() === today.toDateString()) {
          dayDiv.classList.add("today"); // Add a class for today
        }

        calendar.appendChild(dayDiv);
      }
    }

    prevMonth.addEventListener("click", (event) => {
      event.preventDefault();
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar();
    });

    nextMonth.addEventListener("click", (event) => {
      event.preventDefault();
      currentDate.setMonth(currentDate.getMonth() + 1);
      renderCalendar();
    });

    mealOptions.onchange = () => {
      selectedMealPackage = mealOptions.value;
      renderCalendar();
    };

    donateButton.onclick = (event) => {
      event.preventDefault();

      const validDates = Array.from(selectedDates).filter(date => {
        const dateObj = new Date(date);
        dateObj.setHours(0, 0, 0, 0);
        return dateObj >= new Date().setHours(0, 0, 0, 0);
      });

      if (validDates.length > 0) {
        const formattedDates = validDates.join(",");
        donateButton.href = `donation.php?meal=${selectedMealPackage}&dates=${formattedDates}`;
        window.location.href = donateButton.href;
      } else {
        toastr.options = {
          closeButton: true,
          progressBar: true,
          showMethod: 'fadeIn',
          hideMethod: 'fadeOut',
          timeOut: 2000,
        };

        toastr.error('Please select at least one future date for your donation.');


        // alert("Please select at least one future date for your donation.");
      }
    };

    document.addEventListener("DOMContentLoaded", renderCalendar);
  </script>



  <!-- charousal  -->
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
    document.addEventListener("DOMContentLoaded", function() {
      let progressBars = document.querySelectorAll(".progress-bar");
      progressBars.forEach(bar => {
        let percentage = bar.getAttribute("data-percentage"); // Fetch from PHP
        bar.style.width = percentage + "%"; // Set width dynamically
      });
    });
  </script>

  <!-- toaster -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>