<?php ob_start();
include_once './include/header.php';
include_once './class/fileUploader.php';
error_reporting(1);
if (isset($_REQUEST['submit'])) {
    // Ensure the database connection is included

    // Secure input data
    $title = $xssClean->clean_input($_REQUEST['title'] ?? '');
    $count = $xssClean->clean_input($_REQUEST['count'] ?? '');


  
    if ($_REQUEST['id'] > 0) {
        $d_id = $_REQUEST['id'];
        // Update existing event
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE `counter` SET `title`=?, `count`=? WHERE `index_id`=?");
        $stmt->bind_param("ssi", $title, $count, $d_id);
        $stmt->execute();
    } else {
        // Insert new event
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO `counter`(`title`, `count`) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $count);
        $stmt->execute();
        $d_id = $stmt->insert_id;
    }
 
  
    // Image Upload Handling
    // $uploadimage = new ImageUploader($DatabaseCo);
    // $upload_image = is_uploaded_file($_FILES['photos']["tmp_name"]) ? $uploadimage->upload($_FILES['photos'], "events") : '';
  

    //   if ($upload_image != '') {
    //     $DatabaseCo->dbLink->query("UPDATE `event` SET photos='$upload_image' WHERE index_id='$d_id'");
    // }
    //  echo "UPDATE `event` SET photos='$upload_image' WHERE index_id='$d_id'";

    // Redirect based on edit mode
  
 if ($_REQUEST['edit'] > 0) {
    header("location:counter_List.php?alt=1");
} else {
    header("location:counter_add.php");
}

 header("location:counter_List.php");
}





if ($_REQUEST['id'] > 0) {
    // $titl = "Update ";
    $select = "SELECT * FROM counter WHERE index_id='" . $_REQUEST['id'] . "'"; //echo $select;
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
                    <h5 class="card-header">Count Update</h5>
                    <div class="card-body">
                        <form action="" name="finish-form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                            <div class="row  p-3 ">
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-fullname">Title</label>
                                    <input type="text" class="form-control" name="title" id="basic-default-fullname" value="<?php echo $Row->title; ?>" placeholder="Title" />
                                </div>
                                <div class="col-6 mt-2">
                                    <label class="form-label" for="basic-default-company">Count</label>
                                    <input type="Tel" class="form-control" name="count" id="basic-default-company" value="<?php echo $Row->count; ?>" maxlength="10" placeholder="Enter the Count" />
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