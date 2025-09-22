<?php
session_start();

if (isset($_SESSION['msg_alert'])) {
    $msg_alert = $_SESSION['msg_alert'];
}

session_unset(); 
session_destroy(); 

if (isset($msg_alert)) {
    $_SESSION['msg_alert'] = $msg_alert;
}

if (!empty($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: ../index.php");
}
exit;
?>