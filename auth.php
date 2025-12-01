
<?php
// auth.php — CEK LOGIN ADMIN
session_start();
function cekLogin() {
    return isset($_SESSION['admin_login']) && $_SESSION['admin_login'] === true;
}
?>