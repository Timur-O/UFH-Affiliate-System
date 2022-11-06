<?php
session_start();

if (isset($_POST['newEmail'])) {
    /**
     * Declare variables imported from config.php
     *
     * @var $conn mysqli The database connection variable.
     * @var $affiliateTableName string The name of the affiliate table
     */
    include 'config.php';

    $newEmail = $conn->real_escape_string($_POST['newEmail']);

    $sql = "UPDATE `$affiliateTableName` SET `payoutEmail` =  `$newEmail` WHERE `affiliateID` = {$_SESSION['userRefCode']}";
    $result = $conn->query($sql);

    header("Location: payout.php");
    die();
}