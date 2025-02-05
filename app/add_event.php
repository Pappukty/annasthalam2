<?php ob_start();
include_once './include/header.php';
include_once './class/fileUploader.php';
error_reporting(1);
if (isset($_REQUEST['submit'])) {
    // Ensure the database connection is included

    // Secure input data
    $title = $xssClean->clean_input($_REQUEST['title'] ?? '');
    $phone = $xssClean->clean_input($_REQUEST['phone'] ?? '');
    $email = $xssClean->clean_input($_REQUEST['email'] ?? '');
    $amount = $xssClean->clean_input($_REQUEST['amount'] ?? '');

    $goal_amount = $xssClean->clean_input($_REQUEST['goal_amount'] ?? '');
    $date = $xssClean->clean_input($_REQUEST['date'] ?? '');
    $description = $xssClean->clean_input($_REQUEST['description'] ?? '');
    if ($_REQUEST['id'] > 0) {
        $d_id = $_REQUEST['id'];
        // Update existing event
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE `event` SET `title`=?, `phone`=?, `email`=?, `amount`=?, `goal_amount`=?, `date`=?, `description`=? WHERE `index_id`=?");
        $stmt->bind_param("sssssssi", $title, $phone, $email, $amount, $goal_amount, $date, $description, $d_id);
        $stmt->execute();
    } else {
        // Insert new event
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO `event`(`title`, `phone`, `email`, `amount`, `goal_amount`, `date`, `description`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $phone, $email, $amount, $goal_amount, $date, $description);
        $stmt->execute();
        $d_id = $stmt->insert_id;
    }
 
  
    // Image Upload Handling
    $uploadimage = new ImageUploader($DatabaseCo);
    $upload_image = is_uploaded_file($_FILES['photos']["tmp_name"]) ? $uploadimage->upload($_FILES['photos'], "events") : '';
  

      if ($upload_image != '') {
        $DatabaseCo->dbLink->query("UPDATE `event` SET photos='$upload_image' WHERE index_id='$d_id'");
    }
     echo "UPDATE `event` SET photos='$upload_image' WHERE index_id='$d_id'";

    // Redirect based on edit mode
  
 if ($_REQUEST['edit'] > 0) {
    header("location:event_list.php?alt=1");
} else {
    header("location:add_event.php");
}

 header("location:event_list.php");
}





if ($_REQUEST['id'] > 0) {
    // $titl = "Update ";
    $select = "SELECT * FROM event WHERE index_id='" . $_REQUEST['id'] . "'"; //echo $select;
    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
    $Row = mysqli_fetch_object($SQL_STATEMENT);
} else {
    $titl = "";
}
?>

<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h5 class="card-header">Add Event</h5>
                    <div class="card-body">
                        <form action="" name="finish-form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row  p-3 ">
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-fullname">Full Name</label>
                                    <input type="text" class="form-control" name="title" id="basic-default-fullname" value="<?php echo $Row->title; ?>" placeholder="Name" />
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-company">Phone</label>
                                    <input type="Tel" class="form-control" name="phone" id="basic-default-company" value="<?php echo $Row->phone; ?>" maxlength="10" placeholder="Enter the Phone" />
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-email">Email</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            type="text"
                                            id="basic-default-email"
                                            class="form-control"
                                            placeholder="Email"
                                            aria-label="john.doe"
                                            value="<?php echo $Row->email; ?>"
                                            name="email"
                                            aria-describedby="basic-default-email2" />
                                        <span class="input-group-text" id="basic-default-email2"></span>
                                    </div>
                                    <!-- <div class="form-text">You can use letters, numbers & periods</div> -->
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-phone">Amount </label>
                                    <input
                                        type="number"
                                        id="basic-default-phone"
                                        class="form-control phone-mask"
                                        placeholder="Enter the  Amount"
                                        value="<?php echo $Row->amount; ?>"
                                        name="amount" />
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-phone">Goal Amount </label>
                                    <input
                                        type="number"
                                        id="basic-default-phone"
                                        class="form-control phone-mask"
                                        placeholder="Enter the Goal Amount"
                                        value="<?php echo $Row->goal_amount; ?>"
                                        name="goal_amount" />
                                </div>

                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-message">Date & Time</label>
                                    <input
                                        class="form-control"
                                        type="datetime-local"
                                        value="2021-06-18T12:30:00"
                                        id="html5-datetime-local-input"
                                        value="<?php echo $Row->date; ?>"
                                        name="date" />
                                </div>

                                <div class="col-6 mt-2">
                                    <div class="custom-file">
                                        <label for="photos" class="form-label">Image</label>
                                        <input class="fileUp fileup-sm uploadlink"
                                            accept=".jpg, .png, image/jpeg, image/png"
                                            name="photos"
                                            type="file"
                                            id="photos" />
                                        <label class="custom-file-label" for="photos" style="font-size: 13px;">
                                            Recommended: 350 x 350 px (png, jpg, jpeg).
                                        </label>
                                    </div>

                                </div>
                                <div class="col-12 mt-2">
                                    <label class="form-label" for="basic-default-message">Description</label>
                                    <textarea
                                        id="basic-default-message"
                                        class="form-control"

                                        name="description"
                                        placeholder="Enter the Description"><?php echo $Row->description; ?></textarea>
                                </div>

                                <div class="col-4">
                                    <button type="submit" name="submit" class="btn btn-primary mt-4">Send</button>
                                </div>


                            </div>

                        </form>


                    </div>
                </div>
            </div>




        </div>
    </div>
</div>
<script>
       $('#photos').change(function(e) {
        var geekss = e.target.files[0].name;
        $("#imageName").text(geekss + ' is chosen.');
    });
</script>

<?php
include_once './include/footer.php';


?>