<?php 

include_once './include/header.php';
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 1);
error_reporting(1);
// Initialize database connection and XSS cleaner
session_start();


// Fetch existing data to prepopulate the form
$query = "SELECT email, phone_number, address, fb_link, instagram,logo FROM business_setting WHERE index_id = 1";
$result = $DatabaseCo->dbLink->query($query);
$data = $result->fetch_assoc();

?>


  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />




        <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <h5 class="card-header">Add Setting</h5>
                            <div class="card-body">
                                <form action="" method="post" name="add_form" id="add_form" enctype="multipart/form-data">
                                    <div class="row">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div>
                                                        <h4 class="header-title">Settings</h4>
                                                        <p class="card-title-desc">Business Settings</p>
                                                    </div>

                                                    <div class="container">
                                                        <?php if (isset($_SESSION['success_message'])) {
                                                            echo '<div class="alert alert-success">';
                                                            echo $_SESSION['success_message'];
                                                            echo '</div>';

                                                            // Unset the success message so it doesn't show again on refresh
                                                            unset($_SESSION['success_message']);
                                                        } ?>
                                                        <div class="row">
                                                            <div class="col-sm-4 mb-3">
                                                                <label>Email</label>
                                                                <div class="field">
                                                                    <div class="control has-icons-left">
                                                                        <input type="email" class="form-control" name="email" id="email" required placeholder="Enter Email" value="<?php echo ($data['email']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 mb-3">
                                                                <label>Phone Number</label>
                                                                <div class="field">
                                                                    <div class="control has-icons-left">
                                                                        <input type="tel" class="form-control" name="phone_number" maxlength="11" id="phone_number" required placeholder="Enter Number" value="<?php echo ($data['phone_number']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 mb-3">
                                                                <label>Address</label>
                                                                <div class="field">
                                                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter Address"><?php echo ($data['address']); ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 mb-3">
                                                                <label>FB Link</label>
                                                                <div class="field">
                                                                    <div class="control has-icons-left">
                                                                        <input type="text" class="form-control" name="fb_link" id="fb_link" placeholder="Enter FB Link" value="<?php echo ($data['fb_link']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 mb-3">
                                                                <label>Instagram Link</label>
                                                                <div class="field">
                                                                    <div class="control has-icons-left">
                                                                        <input type="text" class="form-control" name="instagram" id="instagram" placeholder="Enter instagram" value="<?php echo ($data['instagram']); ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                      
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-end ">
                                                            <button type="submit" class="btn btn-primary ml-3">Update</button>
                                                        </div>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>

                                    </div>

                                </form>


                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    


<?php
include_once './include/footer.php';

?>
<!-- toaster -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#add_form').submit(function(event) {
        event.preventDefault(); // Prevent normal form submission

        // Collect form data
        var formData = {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token (for Laravel)
            email: $('#email').val(),
            phone_number: $('#phone_number').val(),
            address: $('#address').val(),
            fb_link: $('#fb_link').val(),
            instagram: $('#instagram').val(),
        };

        console.log("Submitting Form Data:", formData);

        // Send AJAX request
        $.ajax({
            url: 'business_process.php', // PHP script URL
            method: 'POST',
            data: formData,
            dataType: 'json', // Expecting JSON response
            success: function(response) {
                console.log("Server Response:", response);

                // Configure Toastr options
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut',
                    timeOut: 2000, // Show message for 2 seconds
                };

                if (response.success) {
                    toastr.success(response.message);
                    // Reload page after 2.5 seconds
                    setTimeout(function() {
                        window.location.reload();
                    }, 2500);
                } else if (response.message) {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log("XHR Error:", xhr.responseText);

                // Configure Toastr error notification
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'fadeIn',
                    hideMethod: 'fadeOut',
                    timeOut: 3000, // Show error for 3 seconds
                };

                toastr.error("An unexpected error occurred. Please try again!");
            }
        });
    });
});



</script>