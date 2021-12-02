<?php
/** CART Code */

session_start();
require_once 'database.php';

// if not logged in issue error
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    // First check if user has cart
    CreateCart($pdo, $_SESSION['userID']);
    // Get BookID to be added to cart 
    $BookID = $_GET['BookID'];

    $pdo->beginTransaction();
    // Check that the book isn't in the user's Cart
    $stmt = $pdo->prepare(
        "SELECT CartItemID FROM cartitem AS ci
INNER JOIN cart AS c ON ci.CartID = c.CartID
INNER JOIN users AS u ON c.userID = u.userID 
WHERE c.userID=:userID AND c.Purchased=0 AND ci.BookID=:BookID"
    );
    $stmt->bindParam(":userID", $_SESSION['userID']);
    $stmt->bindParam(":BookID", $BookID);
    $stmt->execute();
    $Check = $stmt->fetchAll();
    // if it is found then alert the user!
    if (!empty($Check) ) {
        die("This Book is already in your Cart!");
    }
    // First get Cart ID
    $result = readQuery(
        $pdo,
        "SELECT * FROM cart WHERE userID=" . $_SESSION['userID'] . " AND Purchased=0"
    );
    $row = $result->fetch();
    // First Add item to cartitem table
    // To do that get price
    $result = readQuery(
        $pdo,
        "SELECT price FROM books WHERE BookID=" . $BookID
    );
    $Price = $result->fetch();

    // Then insert into cartitem
    $sql = "INSERT INTO cartitem ( BookID, CartID, price)
    VALUES ( :BookID, :CartID, :price )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":BookID", $BookID);
    $stmt->bindParam(":CartID", $row['CartID']);
    $stmt->bindParam(":price", $Price['price']);
    $stmt->execute();

    // Then Update the date in the cart 
    $stmt = $pdo->prepare(
        "UPDATE cart SET CreatedDate=NOW() WHERE userID=:userID AND Purchased=0"
    );
    $stmt->bindParam(":userID", $_SESSION['userID']);
    $stmt->execute();
    $pdo->commit();
    echo "SUCCESS Item added to Cart!";
} else {
    echo "Please Login First!";
}