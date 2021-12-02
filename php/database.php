<?php


if (!isset($_SESSION)) {
    session_start();
}
// File for database connection
try {
    
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    // Database connection
    $pdo = new PDO('mysql:host=' . $dbhost .';dbname=project', $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("SET CHARACTER SET utf8");      // Sets encoding UTF-8
} catch (PDOException $e){
    // in case of error
    $_SESSION['ERROR']= "DB Error!: " . $e->getMessage() . "<br/>";
    die();
 }

function readQuery( $dbHandle, $query) {
    return $dbHandle->query($query);
}

function writeQuery($dbHandle, $query, $params){
    $stmt = $dbHandle->prepare($query);
    return $stmt->execute($params);
}

function CreateCart($db, $userID)
{
    try {
        // First Check if user has cart
        $result = readQuery(
            $db,
            "SELECT * FROM cart WHERE userID=" . $_SESSION['userID'] . " AND Purchased=0"
        );
        $row = $result->fetch();

        // if no cart was found create one for user.
        if ($row == false) {
            $sql = "INSERT INTO cart(userID, CreatedDate, Total, Purchased)
         VALUES(:userID, NOW(), 0, 0)";
            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":userID", $userID);
            $stmt->execute();
            $db->commit();
        }
    } catch (PDOException $e) {
        die($sql . "<br>" . $e->getMessage());
    }
}
