<?php
include_once './class/databaseConn.php';
include_once './class/fileUploader.php';
$DatabaseCo = new DatabaseConn();
include_once './class/XssClean.php';
$xssClean = new xssClean();

error_reporting(1);

if ($_REQUEST['action'] == 'login') {
    $username = isset($_POST['username']) ? strtolower(trim($_POST['username'])) : "";
    $int = (int) filter_var($username, FILTER_SANITIZE_NUMBER_INT);
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    $STATUS_MESSAGE = "";

    if ($_POST['username'] == 'annasthalam') {
        $query = "select * from admin where ( LOWER(username)='" . $username . "') and password='" . md5($password) . "'";
        $SQL_STATEMENT = $DatabaseCo->dbLink->query($query);
        $num = mysqli_num_rows($SQL_STATEMENT);
        if ($num != 0) {
            $_SESSION["admin_id"] = "1";
            echo "success";
        }
    } else {
        $query = "select * from cab_staff where ( LOWER(index_id)='" . $int . "') and password='" . base64_encode($password) . "'";
        $SQL_STATEMENT = $DatabaseCo->dbLink->query($query);
        $num2 = mysqli_num_rows($SQL_STATEMENT); //echo $num2; 
        if ($num2 > 0) {
            $_SESSION["admin_id"] = "";
            $DatabaseCo->dbRow = mysqli_fetch_object($SQL_STATEMENT);
            $_SESSION["staff_id"] = $DatabaseCo->dbRow->index_id;
            echo "success";
        }
    }
    if ($num == 0 && $num2 == 0) {
        echo "failed";
    }
}
if (isset($_REQUEST['submit'])) {
    $salt = "";
    $oldpass = trim($_REQUEST['oldpassword']);
    $oldpass = md5($salt . $oldpass);
    $newpass = trim($_REQUEST['newpassword']);
    $newpass = md5($salt . $newpass);
    $num = mysqli_num_rows(mysqli_query($DatabaseCo->dbLink, "select id from superadmin where `password`='$oldpass'"));
    if ($num > 0) {
        $sql = "update superadmin set `password`='$newpass' where `password`='$oldpass' and id='1'";
        $go = mysqli_query($DatabaseCo->dbLink, $sql);
        echo "<script>window.location='account_setting.php?pwdalt=1'</script>";
    } else {
        echo "<script>window.location='account_setting.php?pwdalt=2'</script>";
    }
}
if (isset($_REQUEST['state_id'])) {
    $SQL_STATEMENT_STATE =  $DatabaseCo->dbLink->query("SELECT state_code FROM state WHERE state_id='" . $_REQUEST['state_id'] . "'");
    $state = mysqli_fetch_assoc($SQL_STATEMENT_STATE);
?>
    <option value="">Select City</option>
    <?php
    $SQL_STATEMENT_city =  $DatabaseCo->dbLink->query("SELECT * FROM city WHERE state_code='" . $state['state_code'] . "' ORDER BY city_name ASC");
    while ($DatabaseCo->dbRow = mysqli_fetch_object($SQL_STATEMENT_city)) { ?>
        <option value="<?php echo $DatabaseCo->dbRow->city_id; ?>"><?php echo $DatabaseCo->dbRow->city_name ?></option>
<?php }
}
?>