<?php
include_once './include/header.php';
error_reporting(0);
// Check if the delete form is submitted
if (isset($_POST['delete_now'])) {
    // Get the item ID from the hidden input field in the modal
    $itemId = $_POST['del_t'];

    // SQL Query to delete the item
    $query = "DELETE FROM donation WHERE index_id = ?";
    $stmt = $DatabaseCo->dbLink->prepare($query);

    // Bind the parameter
    $stmt->bind_param("i", $itemId);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect after successful deletion (Optional: You can set a success message)
        header("Location: doner_list.php");
    } else {
        // Error message if something went wrong
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

?>

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .dataTables_filter input {
        width: 300px;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 5px 10px;
    }

    .dt-buttons {
        margin-bottom: 10px;
    }

    .paginate_button {
        padding: 5px 10px !important;
        color: black !important;
    }

    .dataTables_paginate .pagination {
        justify-content: center;
    }

    .dataTables_paginate .paginate_button {
        background-color: #d6d6d6 !important;
        /* Blue background for all buttons */
        color: black !important;
        border-radius: 5px !important;
        margin: 3px;
        padding: 5px 10px;
        border: none !important;
    }

    /* ✅ Remove Hover Effect */
    .dataTables_paginate .paginate_button:hover {

        color: white !important;
    }

    /* ✅ No Different Color for Active Button */
    .dataTables_paginate .paginate_button.current {
        background-color: #007bff !important;
        /* Keep active button same color */
        color: white !important;
    }
</style>
</head>

<div class="container-xxl flex-grow-1 container-p-y">
    <h2 class="mb-3 text-center">Donation List</h2>

    <!-- DataTable -->
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Event</th>
                <!-- <th>Service</th> -->
                <th>Date</th>
                <th>Meals Count</th>
                <th>Total Amount</th>
                <!-- <th>Comments</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $select = "SELECT * FROM `donation` WHERE index_id!='0' ORDER BY index_id DESC";
            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
            $num_rows = mysqli_num_rows($SQL_STATEMENT);
            if ($num_rows != 0) {
                $i = 1;
                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {
                    // Fetch all data from courses table based on category_id
                    // $sql3 = mysqli_query($DatabaseCo->dbLink, "SELECT god_name FROM god WHERE index_id='" . $Row->god_id . "'");
                    // $res3 = mysqli_fetch_object($sql3);
            ?>
                    <tr>
                        <td><?php echo $i;
                            $i++; ?></td>
                        <td><?php echo $Row->name; ?></td>
                        <td><?php echo $Row->email; ?></td>
                        <td><?php echo $Row->phone; ?></td>
                        <td><?php echo $Row->event; ?></td>
                        <!-- <td><?php echo $Row->service_date; ?></td> -->
                        <td><?php echo $Row->dob; ?></td>
                        <td><?php echo $Row->meals_count; ?></td>
                        <td><?php echo $Row->total_amount; ?></td>
                        <!-- <td><?php echo $Row->comments; ?></td> -->
                        <td>
                            <!-- <button class="btn btn-warning btn-sm">Edit</button> -->
                            <!-- Delete Button -->
                            <!-- Delete Button -->
                            <button class="btn btn-sm p-2 btn-danger delete-board alert-box-trigger waves-effect waves-light kill-drop text-white"
                                data-id="<?php echo $Row->index_id; ?>"
                                id="delete-board<?php echo $Row->index_id; ?>">Delete</button>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="9">
                        <div align="center"><strong>No Records!</strong></div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div id="delete-board-alert" class="modal fade alert-box" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="doner_list.php" method="post" name="delete_form" id="delete_form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="header-title">Delete Donar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete Donar Details?</h5>
                    <p>Are you sure you want to delete this Donar? All data will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="Delete" />
                    <input type="hidden" name="del_t" id="delid" value="" /> <!-- Hidden input for the item ID -->
                    <button class="btn raised bg-primary text-white ml-2 mt-2" data-bs-dismiss="modal">Cancel</button>
                    <button name="delete_now" type="submit" class="btn mt-2 btn-dash btn-danger raised has-icon" id="modalDelete" value="Delete">Delete</button>
                </div>
            </div>
        </div>
    </form>

</div>

    <!-- jQuery and Bootstrap Bundle -->

    <!-- jQuery and Bootstrap Bundle -->
  

 


    <!-- datables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "pagingType": "full_numbers",
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search records...",
                    "paginate": {
                        "previous": "Previous",
                        "next": "Next"
                    }
                },
                dom: "<'row'<'col-sm-6'B><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-6'i><'col-sm-6'p>>",
                buttons: [
                    { extend: 'excelHtml5', className: 'btn btn-primary btn-sm', text: 'Export to Excel' },
                    { extend: 'pdfHtml5', className: 'btn btn-primary btn-sm', text: 'Export to PDF' },
                    { extend: 'print', className: 'btn btn-primary btn-sm', text: 'Print' }
                ]
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
        // Initialize the modal instance
        const deleteModal = new bootstrap.Modal(document.getElementById('delete-board-alert'));

        // Event listener for the delete button
        document.querySelectorAll('.delete-board').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id'); // Get the item ID from the data-id attribute

                // Set the item ID into the hidden input field inside the modal
                document.getElementById('delid').value = itemId;

                // Show the modal
                deleteModal.show();
            });
        });
    });
    </script>
 <?php 
include_once './include/footer.php';


?>