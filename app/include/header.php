<?php
//error_reporting(0);
include_once './class/databaseConn.php';
include_once './class/fileUploader.php';

$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();
if (empty($_SESSION['admin_id'])) {
  echo "<script>window.location='index.html';</script>";
  exit();
}

// Get user role and email

// Check user role and fetch corresponding name
if (!empty($_SESSION['admin_id'])) {
  // Admin logic
  $user_role = "Admin";
  $user_name = "Annasthalam"; // Default name for admin
  $user_email = "Annasthalam@gmail.com"; // Replace with admin's default email if needed

  // Staff logic

} else {
  // Unknown user fallback
  $user_role = "Unknown";
  $user_name = "Guest";
  $user_email = "No Email";
}

// // Output for verification
// echo "Role: $user_role<br>";
// echo "Name: $user_name<br>";
// echo "Email: $user_email<br>";

?>








<!doctype html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="./assets/img/logo.jpg"
  data-template="vertical-menu-template-free"
  data-style="light">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Annasthalam</title>

  <meta name="description" content="annasthalam" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/img/logo.jpg" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />
  <link rel="stylesheet" href="./assets/css/calendar.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Page CSS -->
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="./assets/js/config.js"></script>
 
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

  <!-- calendar -->


  <link rel="stylesheet" href="./assets/vendor/css/pages/app-calendar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- endbuild -->

  <link rel="stylesheet" href="./assets/vendor/libs/fullcalendar/fullcalendar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/flatpickr/flatpickr.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/select2/select2.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/quill/editor.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/@form-validation/form-validation.css" />

  <!-- Page CSS -->

  <link rel="stylesheet" href="./assets/vendor/css/pages/app-calendar.css" />
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="dashboard.php" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="./assets/img/logo.jpg" alt="" height="40px" width="40px">
              <path
                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                id="path-1"></path>
              <path
                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                id="path-3"></path>
              <path
                d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                id="path-4"></path>
              <path
                d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                id="path-5"></path>
              </defs>
              <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                  <g id="Icon" transform="translate(27.000000, 15.000000)">
                    <g id="Mask" transform="translate(0.000000, 8.000000)">
                      <mask id="mask-2" fill="white">
                        <use xlink:href="#path-1"></use>
                      </mask>
                      <use fill="#696cff" xlink:href="#path-1"></use>
                      <g id="Path-3" mask="url(#mask-2)">
                        <use fill="#696cff" xlink:href="#path-3"></use>
                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                      </g>
                      <g id="Path-4" mask="url(#mask-2)">
                        <use fill="#696cff" xlink:href="#path-4"></use>
                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                      </g>
                    </g>
                    <g
                      id="Triangle"
                      transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                      <use fill="#696cff" xlink:href="#path-5"></use>
                      <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                    </g>
                  </g>
                </g>
              </g>
              </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-1" style="font-size: 19px;">Annasthalam</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="icon-base bx bx-chevron-left"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboards -->
          <li class="menu-item active open">
            <a href="dashboard.php" class="menu-link menu-">
              <i class="menu-icon tf-icons bx bx-home-smile"></i>
              <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
              <!-- <span class="badge rounded-pill bg-danger ms-auto">5</span> -->
            </a>
            <!-- <ul class="menu-sub">
                <li class="menu-item active">
                  <a href="index.html" class="menu-link">
                    <div class="text-truncate" data-i18n="Analytics">Dashboards</div>
                  </a>
                </li>
              
              </ul> -->
          </li>


          <!-- Apps & Pages -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text"> Pages</span>
          </li>

          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-donate-heart"></i> Donation
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="doner_list.php" class="menu-link">Donation List</a>
              </li>
            </ul>
          </li>

          <!-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-group"></i> Volunteer
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="pages-volunteer-list.html" class="menu-link">Volunteer List</a>
              </li>
            </ul>
          </li> -->

          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-credit-card"></i> Payments
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="payment_list.php" class="menu-link">Payment List</a>
              </li>
            </ul>
          </li>

          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-calendar"></i> User Calendar
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a
                  href="calendar.php"
                  target="_blank"
                  class="menu-link">Calendar List</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-food-menu"></i>Add Event
</a>



            <ul class="menu-sub">
              <li class="menu-item">
                <a
                  href="add_event.php"
                  target="_blank"
                  class="menu-link">Event Add</a>
              </li>
              <li class="menu-item">
                <a
                  href="event_list.php"
                  target="_blank"
                  class="menu-link">Event List</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-image"></i> Gallery
</a>



            <ul class="menu-sub">
              <li class="menu-item">
                <a
                  href="add_gallery.php"
                  target="_blank"
                  class="menu-link">Gallery Add</a>
              </li>
              <li class="menu-item">
                <a
                  href="gallery.php"
                  target="_blank"
                  class="menu-link">Gallery List</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
    <i class="menu-icon tf-icons bx bx-landscape"></i> Poster
</a>







            <ul class="menu-sub">
              <li class="menu-item">
                <a
                  href="add_poster.php"
                  target="_blank"
                  class="menu-link">Poster Add</a>
              </li>
              <li class="menu-item">
                <a
                  href="poster.php"
                  target="_blank"
                  class="menu-link">Poster List</a>
              </li>
            </ul>
          </li>

          <li class="menu-item">
            <a href="counter_List.php" class="menu-link menu-toggl">
            <i class="bx bx-list-ol bx-md me-3"></i>



Count
            </a>


          </li>


          <!-- Components -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Settings</span></li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-cog"></i> Settings
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="setting.php" class="menu-link">Settings</a>
              </li>


            </ul>
          </li>

          <li class="menu-item">
            <a href="profile.php" class="menu-link menu-toggl">
              <i class="bx bx-user bx-md me-3"></i> My Profile
            </a>

          </li>
          <li class="menu-item">
            <a href="logout.php" title="Logout" class="menu-link">
              <i class="bx bx-log-out bx-md me-3"></i> Log Out
            </a>
          </li>


        </ul>
      </aside>

      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
              <i class="bx bx-menu bx-md"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search bx-md"></i>
                <input
                  type="text"
                  class="form-control border-0 shadow-none ps-1 ps-sm-2"
                  placeholder="Search..."
                  aria-label="Search..." />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->
              <!-- <li class="nav-item lh-1 me-4">
                  <a
                    class="github-button"
                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                    >Star</a
                  >
                </li> -->

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                  class="nav-link dropdown-toggle hide-arrow p-0"
                  href="javascript:void(0);"
                  data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="./assets/img/logo.jpg" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="./assets/img/logo.jpg" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-0"> <?php echo ucfirst($user_role); ?></h6>
                          <small class="text-muted"> <?php echo htmlspecialchars($user_email); ?></small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="profile.php">
                      <i class="bx bx-user bx-md me-3"></i><span>My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="setting.php"> <i class="bx bx-cog bx-md me-3"></i><span>Settings</span> </a>
                  </li>
                  <!-- <li>
                    <a class="dropdown-item" href="#">
                      <span class="d-flex align-items-center align-middle">
                        <i class="flex-shrink-0 bx bx-credit-card bx-md me-3"></i><span class="flex-grow-1 align-middle">Billing Plan</span>
                        <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                      </span>
                    </a>
                  </li> -->
                  <li>
                    <div class="dropdown-divider my-1"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="logout.php" title="Logout" class="dropdown-item">
                      <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">