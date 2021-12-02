<?php
/** File to update user info */
session_start();

require_once 'database.php';
require_once 'page.php';

// When clicked on submit
if (isset($_POST['Update']) && $_POST['Update'] === "User" ) {
    // if not logged in
    if (!isset($_SESSION['userID'])) {
        $MSG = "Please Login!";
        // return to main page
        header('Location: ' . $HOMEPAGE . "?MSG=$MSG");
    }
    if (isset($_POST['OldPass'])) {
        // Check User Pass!
        $OldPass = hash('sha256', ($_POST['OldPass']));
        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE  
		 userID=:id AND password = :Pass"
        );
        $stmt->bindParam(
            ":id",
            $_SESSION['userID']
        );
        $stmt->bindParam(
            ":Pass",
            $OldPass,
            PDO::PARAM_STR
        );
        $stmt->execute();

        // Get data from the result
        $row = $stmt->fetch();
        // if its empty means no user found
        if ($row == false) {
            $MSG="Please Check Your Password!";
            header('Location: ' . $EditInfoPage . "?MSG=$MSG");
        }
    }
    if ($_POST['Pass'] != $_POST['ConfirmPass'] ) {
            $MSG="Password and Confirm Password don't match!";
            header('Location: ' . $EditInfoPage . "?MSG=$MSG");
    }
    $stmt = $pdo->prepare(
        "UPDATE users SET Name=:name, password=:pass, 
age=:age,phoneNumber=:ph,Email=:email,DOB=:dob, lastaccess=NOW()
WHERE userID=:userID"
    );

    $Pass = hash('sha256', ($_POST['Pass']));
    $stmt->bindParam(":name", $_POST['Name']);
    $stmt->bindParam(":pass", $Pass);
    $stmt->bindParam(":age", $_POST['agE']);
    $stmt->bindParam(":ph", $_POST['ph']);
    $stmt->bindParam(":email", $_POST['Email']);
    // Concanecate Date in a MM/DD/YYYY format
    $date = strtotime($_POST['MM'] . "/" . $_POST['Day'] . "/" . $_POST['YY']);
    // Then set it to the right format for mysql
    $DateOfBirth = date('Y-m-d', $date);
    $stmt->bindParam(":dob", $DateOfBirth);
    $stmt->bindParam(":userID", $_SESSION['userID']);
    if ($stmt->execute()) {
        if (isset($_SERVER["HTTP_REFERER"])) {
            header("Location: " . $_SERVER["HTTP_REFERER"]);
        }
        $MSG="Please Relogin!";
        if (isset($_SESSION['ADMIN'])) {

        } else {
            include_once '../php/logout.php';
            header("location: " . $HOMEPAGE . "?MSG=$MSG");
        }
    } else {
        die(var_dump($stmt->errorInfo()));
    }
}

if (isset($_POST['Cancel'])) {
    header("location: " . $ProfilePage);
}