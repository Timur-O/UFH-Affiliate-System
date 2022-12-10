<?php
    /**
     * @var mysqli $conn the DB Connection
     * @var string $userTableName the name of the user table
     * @var string $primaryKeyColumn the column containing primary key information
     */

    if(!isset($_SESSION)) {
        session_start();
    }

    // Session Fixation Safeguard
    if (!isset($_SESSION['initialized'])) {
        session_regenerate_id();
        $_SESSION['initialized'] = true;
    }

    if (!isset($_SESSION['connectedByAdmin']) || $_SESSION['connectedByAdmin'] == false) {
        // Session Hijacking Safeguard
        $seed = "SuperSecretSeed";
        if (isset($_SESSION['HTTP_USER_AGENT'])) {
            if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'] . $seed)) {
                // Redirect to login
                session_destroy();
                header("Location: index.php"); die();
            }
        } else {
            $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT'] . $seed);
        }
    }

    if (isset($_SESSION['user'])) {
        $user = test_input($_SESSION['user']);

        // Session Takeover Safeguard
        $getLastPasswordChangeTimeSQL = "SELECT `passChangedTimestamp` FROM `$userTableName` WHERE `$primaryKeyColumn` = '$user'";
        $getLastPasswordChangeTimeResult = $conn->query($getLastPasswordChangeTimeSQL)->fetch_assoc();

        $lastPasswordChangeTime = $getLastPasswordChangeTimeResult['passChangedTimestamp'];

        if (($_SESSION['loginTime'] - $lastPasswordChangeTime) < 0) {
            session_destroy();
            header("Location: index.php"); die();
        }
    } else {
        header("Location: index.php"); die();
    }