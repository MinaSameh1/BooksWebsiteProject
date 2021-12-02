<?php
/** This is a file to handle Purchase */

require_once 'database.php';
require_once 'page.php';


if (!isset($_GET['ID']) ) {
    header('Location: ' . $HOMEPAGE . "?MSG='Please Sign in'");
}
$result = readQuery(
    $pdo,
    // Count how many items are there
    "SELECT COUNT(CartItemID) AS itemsNumber FROM cartitem AS ci
INNER JOIN cart AS c ON ci.CartID=c.CartID
WHERE c.userID=$_GET[ID] AND Purchased=0
"
);

$row = $result->fetch();
if ($row['itemsNumber'] == "0" ) {
    header('Location: ' . $HOMEPAGE . "?MSG=No Books were Found in cart!");
}

$stmt = $pdo->prepare(
    "UPDATE cart SET Purchased=b'1' WHERE userID=:userID AND Purchased=0"
);


$stmt->bindParam(":userID", $_GET['ID']);
if (!$stmt->execute() ) {
    header('Location: ' . $HOMEPAGE . "?MSG=No Cart was found!");
}

header('Location: ' . $HOMEPAGE);