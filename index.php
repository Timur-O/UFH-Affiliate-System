<?php
session_start();

if (isset($_SESSION['user'])) {
    require_once 'config.php';
    
    $_SESSION['affiliateLogin'] = false;

    $user = test_input($_SESSION['user']);
    $sql = "SELECT `$emailColumn` FROM `$userTableName` WHERE `$primaryKeyColumn` = $user";
    $result = $conn->query($sql) or die($conn->error);
    $result = $result->fetch_assoc();
    $email = $result[$emailColumn];

    $_SESSION['userRefCode'] = $_SESSION['user'];
    $_SESSION['email'] = $email;

    // Redirect to Inner Affiliate Pages
    header("Location: overview.php"); die();
} else {
    $_SESSION['affiliateLogin'] = true;

    // Redirect to main login page
    header("Location: https://app.ultifreehosting.com/login.php"); die();
}