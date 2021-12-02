<?php

session_start();

require_once "database.php";
require_once "page.php";

if (
    isset($_POST['name']) &&
    isset($_POST['phone']) &&
    isset($_POST['email']) &&
    isset($_POST['Date']) &&
     isset($_POST['age']) &&
    isset($_POST['UserName']) &&
     isset($_POST['Password']) &&
    isset($_POST['ConfirmPass'])
)
{ 
    if ($_POST['Password'] === $_POST['ConfirmPass'] ) {
        // First check that user doesn't exist
        $sql = "SELECT * FROM users WHERE UserName=:un OR Email=:em";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':un', $_POST["UserName"]);
        $stmt->bindParam(':em', $_POST["email"]);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row != false) {
            header('Location: ' . $HOMEPAGE . "?MSG=the username/email is already taken!");
        }
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare(
            "INSERT INTO users(
            Name,phoneNumber,Email,DOB,age,userName,password)
            values(:N,:PN,:E,:DO,:AGE,:UN,:PASS)"
        );
        $stmt->bindParam(':N', $_POST["name"]);
        $stmt->bindParam(':PN', $_POST["phone"]);
        $stmt->bindParam(':E', $_POST["email"]);
        // Set the date to mysql Format 
        $date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["Date"])));
        $stmt->bindParam(':DO', $date);
        $stmt->bindParam(':AGE', $_POST["age"]);
        $stmt->bindParam(':UN', $_POST["UserName"]);
        $Pass = hash('sha256', $_POST['Password']);
        $stmt->bindParam(':PASS', $Pass);
        if ($stmt->execute()) {
            $userID = $pdo->lastInsertId();
            foreach ($_POST['Genres'] as $genreID) {
                $sql = "INSERT INTO `project`.`intersets` (`userID`, `genreID`)
             VALUES ($userID, $genreID) ";
                $pdo->prepare($sql)->execute();
            }
            $pdo->commit();
            header('Location: ' . $HOMEPAGE . "?MSG=Registered Successfully");
        } else {
            $_SESSION['ERROR'] = "Error!: " . var_dump($stmt) . "<br/>";
        }
    } else {
        header('Location: ' . $HOMEPAGE . "?MSG=Password and Confirm Password are not the same!");
    }
} else {
    header('Location: ' . $HOMEPAGE . "?MSG=Registeration Failed! Please fill the form");
}
