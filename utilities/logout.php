<?php
$loginPageLocation = '../user-pages/authentication/';

// 1. Ensure a session is started before trying to manipulate it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();
header('Location: ' . $loginPageLocation);
exit();

?>