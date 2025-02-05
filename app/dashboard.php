<?php ob_start();
include_once './include/header.php';


?>
<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-xxl-12 mb-6 order-0">
      <div class="card">
        <div class="d-flex align-items-start row">
          <div class="col-sm-7" id="adminContent" style="display: none;">
            <div class="card-body">
              <h5 class="card-title text-primary mb-3">Thank You, Admin! 🙌</h5>
              <p class="mb-6">
                Your leadership and efforts are making a huge impact! Monitor donations and manage contributions effectively.<br />
                Explore new insights and reports now.
              </p>
              <a href="javascript:;" class="btn btn-sm btn-outline-primary">Manage Donations</a>
            </div>
          </div>

          <script>
            // Simulating an admin check (Replace this with actual logic)
            const isAdmin = true; // Set this based on real authentication logic

            if (isAdmin) {
              document.getElementById("adminContent").style.display = "block";
            }
          </script>

          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-6">
              <img
                src="./assets/img/illustrations/man-with-laptop.png"
                height="175"
                class="scaleX-n1-rtl"
                alt="View Badge User" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <img
                    src="./assets/img/icons/unicons/chart-success.png"
                    alt="chart success"
                    class="rounded" />
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt3"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <p class="mb-1">Donor</p>
              <h4 class="card-title mb-3">578</h4>
              <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <img
                    src="./assets/img/icons/unicons/wallet-info.png"
                    alt="wallet info"
                    class="rounded" />
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt6"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <p class="mb-1">Today Donation</p>
              <h4 class="card-title mb-3">265</h4>
              <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +28.42%</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <img src="./assets/img/icons/unicons/upi.png" alt="paypal" class="rounded" />
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt4"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <p class="mb-1">Payments</p>
              <h4 class="card-title mb-3">₹2,456</h4>
              <small class="text-danger fw-medium"><i class="bx bx-down-arrow-alt"></i> -14.82%</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <img src="./assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="cardOpt1"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="cardOpt1">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <p class="mb-1">Transactions</p>
              <h4 class="card-title mb-3">₹14,857</h4>
              <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
            </div>
          </div>
        </div>

      </div>
    </div>
    <!-- Total Revenue -->

    <!--/ Total Revenue -->
    <div class="col-lg-6 col-md-4 order-2">
      <div class="row">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Transactions</h5>
            <div class="dropdown">
              <button
                class="btn text-muted p-0"
                type="button"
                id="transactionID"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <i class="bx bx-dots-vertical-rounded bx-lg"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
              </div>
            </div>
          </div>
          <div class="card-body pt-4">
            <ul class="p-0 m-0">
              <li class="d-flex align-items-center mb-6">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="./assets/img/icons/unicons/upi.png" alt="User" class="rounded" />
                </div>

                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small class="d-block">Upi Payment</small>
                    <h6 class="fw-normal mb-0">Send money</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <h6 class="fw-normal mb-0">8654.69</h6>
                    <span class="text-muted">INA</span>
                  </div>
                </div>
              </li>
              <li class="d-flex align-items-center mb-6">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="./assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small class="d-block">Wallet</small>
                    <h6 class="fw-normal mb-0">Mac'D</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <h6 class="fw-normal mb-0">2700.69</h6>
                    <span class="text-muted">INA</span>
                  </div>
                </div>
              </li>
              <li class="d-flex align-items-center mb-6">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="./assets/img/icons/unicons/chart.png" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small class="d-block">Transfer</small>
                    <h6 class="fw-normal mb-0">Refund</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <h6 class="fw-normal mb-0">6375.91</h6>
                    <span class="text-muted">INA</span>
                  </div>
                </div>
              </li>
              <li class="d-flex align-items-center mb-6">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="./assets/img/icons/unicons/cc-primary.png" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small class="d-block">Credit Card</small>
                    <h6 class="fw-normal mb-0"> Food Donation</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <h6 class="fw-normal mb-0">8378.71</h6>
                    <span class="text-muted">INA</span>
                  </div>
                </div>
              </li>
              <li class="d-flex align-items-center mb-6">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="./assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small class="d-block">Wallet</small>
                    <h6 class="fw-normal mb-0">Starbucks</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <h6 class="fw-normal mb-0">4203.33</h6>
                    <span class="text-muted">INA</span>
                  </div>
                </div>
              </li>
              <li class="d-flex align-items-center">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="./assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <small class="d-block">Mastercard</small>
                    <h6 class="fw-normal mb-0">Food Donation</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-2">
                    <h6 class="fw-normal mb-0">7092.45</h6>
                    <span class="text-muted">INA</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- / Content -->

<?php
include_once './include/footer.php';


?>