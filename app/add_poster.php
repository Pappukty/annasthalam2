<?php ob_start();
include_once './include/header.php';
include_once './class/fileUploader.php';
error_reporting(1);
if (isset($_REQUEST['submit'])) {
    // Ensure the database connection is included

    $d_id = $_REQUEST['id'] ?? 0;

    // Image Upload Handling
    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']["tmp_name"])) {
        $uploadimage = new ImageUploader($DatabaseCo);
        $upload_image = $uploadimage->upload($_FILES['image'], "poster");

        if (!empty($upload_image)) {
            if ($d_id > 0) {
                // Update existing image
                $stmt = $DatabaseCo->dbLink->prepare("UPDATE `poster` SET image=? WHERE index_id=?");
                $stmt->bind_param("si", $upload_image, $d_id);
                $stmt->execute();
            } else {
                // Insert new image
                $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO `poster`(`image`) VALUES (?)");
                $stmt->bind_param("s", $upload_image);
                $stmt->execute();
                $d_id = $stmt->insert_id;
            }
        }
    }

    // Redirect
    header("location:poster.php");
    exit;
}






if ($_REQUEST['id'] > 0) {
    // $titl = "Update ";
    $select = "SELECT * FROM poster WHERE index_id='" . $_REQUEST['id'] . "'"; //echo $select;
    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
    $Row = mysqli_fetch_object($SQL_STATEMENT);
} else {
    $titl = "";
}
?>
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
  
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Add poster</h5>
                    <div class="card-body">
                        <form action="" name="finish-form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row  p-3 ">
                                

                                <div class="col-6 ">
                                    <div class="custom-file">
                                        <label for="photos" class="form-label">Image</label>
                                        <input class=" form-control fileUp fileup-sm uploadlink"
                                            accept=".jpg, .png, image/jpeg, image/png"
                                            name="image"
                                            type="file"
                                            id="photos" />
                                        <label class="custom-file-label" for="photos" style="font-size: 13px;">
                                            Recommended: 250 x 250 px (png, jpg, jpeg).
                                        </label>
                                    </div>

                                </div>
                         

                                <div class="col-6">
                                    <button type="submit" name="submit" class="btn btn-primary mt-5">Send</button>
                                </div>


                            </div>

                        </form>


                    </div>
                </div>
            </div>




        </div>
    </div>
</div>
</div>





</div>


<script src="https://cdn.ckeditor.com/4.25.0/standard/ckeditor.js"></script>


<?php
include_once './includes/footer.php';

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#poster-form').on('submit', function (e) {
        e.preventDefault(); // Prevent page reload on form submission

        // Determine action based on whether `index_id` has a value
        let action = $('#index_id').val() ? 'update' : 'insert';

        // Create FormData object to handle file upload and form data
        let formData = new FormData(this);
        formData.append('action', action);

        $.ajax({
            url: 'poster_process.php',
            type: 'POST',
            data: formData,
            contentType: false, // Needed to handle file upload
            processData: false, // Needed to handle file upload
            success: function (response) {
                try {
                    let res = JSON.parse(response);
                    if (res.success) {
                        showMessage('success', res.message); // Show success message

                        // Redirect to another page after 2 seconds (adjust URL as needed)
                        setTimeout(() => {
                            window.location.href = 'poster_list.php'; // Change to your target page
                        }, 2000);
                    } else {
                        showMessage('danger', res.message); // Show error message
                    }
                } catch (e) {
                    // showMessage('danger', 'Invalid response from server.');
                }
            },
            error: function (xhr, status, error) {
                showMessage('danger', 'An error occurred: ' + error);
            }
        });
    });

    // Function to show messages (success or error)
    function showMessage(type, message) {
        let alertBox = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        $('#message-container').html(alertBox);

        // Auto-hide alert after 5 seconds
        setTimeout(() => $('.alert').alert('close'), 5000);
    }
});
</script>
<script>
$('.remove').click(function() {
  // Get the data attributes to identify the image and poster row
  var imageIndex = $(this).data('index'); // The row's index_id
  var nameIndex = $(this).data('name'); // The specific image name

  // Send AJAX request to the server to remove the specific image using POST
  $.ajax({
    type: "POST",
    url: "remove_poster.php", // Server endpoint to handle image removal
    data: {
      imageIndex: imageIndex,
      nameIndex: nameIndex
    },
    success: function(response) {
      try {
        var result = JSON.parse(response);
        if (result.status === 'success') {
        //   alert(result.message);  // Display success message

          // Reload the current page to reflect changes
          window.location.reload();
        } else {
          alert(result.message);  // Display failure message
        }
      } catch (e) {
        console.log("Parsing Error:", e);
        window.location.reload();
      }
    },
    error: function(error) {
      console.log("Error removing image:", error);
      window.location.reload();
    }
  });
});

;
</script>