<?php

/**
 * Login Check File
 */

session_start();
// Database connection
require_once 'database.php';
// Page info
require_once 'page.php';

$Login = $_POST['Login'];
$password = hash('sha256', ($_POST['Pass']));

try {
    // Search for user by UserName OR email and compare encrypted password
    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE  
		( userName=:name OR Email = :email) AND password = :Pass"
    );
    $stmt->bindParam(":name", $Login, PDO::PARAM_STR);
    $stmt->bindParam(":email", $Login, PDO::PARAM_STR);
    $stmt->bindParam(":Pass", $password, PDO::PARAM_STR);

    $stmt->execute();

    // Get data from the result
    $row = $stmt->fetch();
    // if its empty means no user found
    if ($row == false) {
        $_SESSION['ERROR'] = "Please Check your Login info!";
    } elseif ($row['UserBlocked'] == 1) {
        // User blocked! 
        $_SESSION['ERROR'] 
            = "You have been blocked, please contact admin of the page";
    } else {
        $stmt = $pdo->prepare(
            "UPDATE users SET lastaccess=NOW() WHERE userID=:userID"
        );
        $stmt->execute(array('userID' => $row['userID']));
        // save the UserID, username and email.
        $_SESSION["logged_in"] = true;
        $_SESSION['name'] = $row['Name'];
        $_SESSION["userName"] = $row['userName'];
        $_SESSION["Email"] = $row['Email'];
        $_SESSION["userID"] = $row['userID'];
        if ($row['isAdmin'] != 0 && $row['isAdmin'] != "NULL") {
            $_SESSION['Admin'] = true;
        } else {
            $_SESSION['Admin'] = false;
        }
        $MSG = "false";
    }
} catch (PDOException $e) {
    $_SESSION['ERROR'] = "Error!: " . $e->getMessage() . "<br/>";
}
// return to main page
header('Location: ' . $HOMEPAGE . "?MSG=$MSG");