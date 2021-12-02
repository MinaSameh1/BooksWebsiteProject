<?php /** File to remove Cart item from Cart! */

require_once "database.php";

if (isset($_GET['userID']) && isset($_GET['BookID']) ) {
    // Get the cartItem ID first using this query
    $sql = "SELECT CartItemID FROM cartitem AS ci
INNER JOIN cart AS c ON ci.CartID=c.CartID
INNER JOIN users AS u ON c.userID = u.userID 
WHERE c.userID=:userID AND c.Purchased=0 AND ci.BookID=:BookID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":userID", $_GET['userID']);
    $stmt->bindParam(":BookID", $_GET['BookID']);
    $stmt->execute();
    $row = $stmt->fetch();
    $sql = "DELETE FROM cartitem WHERE CartItemID=:cartItemID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":cartItemID", $row['CartItemID']);
    if (!$stmt->execute() ) {
        echo "Failed to remove item!";
    } else {
        echo "false";
    }
} else {
    echo '"Please Login first!"';
}