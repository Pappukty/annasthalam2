
   
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            max-width: 600px;
            margin: auto;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

<?php
include_once './include/header.php';
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 1);
error_reporting(1);
// Initialize database connection and XSS cleaner


// Fetch admin details dynamically
$query = "SELECT username, email, password FROM admin WHERE id = 1";
$result = $DatabaseCo->dbLink->query($query);

// Check if data exists
$data = $result->fetch_assoc();

?>




<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Admin Profile</h5>
                    <div class="card-body">
                        <div class="container py-5">
                            <div class="card shadow-lg p-4 profile-card text-center">
                                <img src="./assets/img/logo.jpg" alt="Profile Picture" class="profile-img mb-3">
                                <h4 class="mb-1"><?php echo htmlspecialchars($data['username']); ?></h4>
                                <p class="text-muted">Admin | <?php echo htmlspecialchars($data['email']); ?></p>

                                <hr>

                                <div class="text-start">
                                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($data['username']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($data['email']); ?></p>
                                    <p><strong>Role:</strong> Administrator</p>
                                    <p><strong>Password:</strong> ******** <a href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal" class="btn btn-link">Change</a></p>
                                </div>

                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_profile.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (Optional)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <input type="hidden" name="id" value="1"> <!-- Admin ID -->
                    <button type="submit" class="btn btn-success w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include_once './include/footer.php';

?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add_form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally

            // Collect form data
            var formData = {
                _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token, if using Laravel
                email: $('#email').val(),
                phone_number: $('#phone_number').val(),
                address: $('#address').val(),
                fb_link: $('#fb_link').val(),
                instagram: $('#instagram').val(),

            };

            console.log("Form Data Submitted:", formData);

            // Send AJAX request
            $.ajax({
                url: 'business_process.php', // Path to your PHP script
                method: 'POST',
                data: formData,
                dataType: 'json', // Expecting a JSON response from the server
                success: function(response) {
                    console.log("Server Response:", response);

                    if (response.data) {
                        // Handle success
                        // alert(response.message); // Show success message

                        // Reload the page to reflect changes
                        window.location.reload(); // This will reload the current page
                    } else {
                        // Handle failure

                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    window.location.reload();
                    // console.log("XHR Response:", xhr.responseText);

                }
            });
        });
    });
</script>