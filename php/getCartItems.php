<?php
/** CODE TO GET CART ITEMS sent to webpage using ajax*/

require_once '../php/database.php';

// SQL Query to get the items just as we want them :) 
$sql = "
SELECT u.userID, b.BookID, b.cover, b.title, ci.price, 
(SELECT SUM(price) FROM cartitem WHERE CartID=:CartID ) AS Total
FROM cart AS c 
INNER JOIN cartitem AS ci ON ci.CartID=c.CartID
INNER JOIN books AS b ON b.BookID=ci.bookID 
INNER JOIN users AS u ON c.userID=u.userID
WHERE ci.CartID=:CarttID AND u.userID=:userID AND c.Purchased=0";

// Get Cart ID
$result = readQuery(
    $pdo,
    "SELECT * FROM cart WHERE userID=" . $_GET['userID'] . " AND Purchased=0"
);

$row = $result->fetch();
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":CartID", $row['CartID']);
$stmt->bindParam(":CarttID", $row['CartID']);
$stmt->bindParam(":userID", $_GET['userID']);
$stmt->execute();
$rows = $stmt->fetchAll();


if ($rows === false ) {
    echo 'No items found';
} else {
    // Loop add each item as its stand alone container
    foreach ($rows as $row) {
        echo '<div class="containerrer" style="height: 185px;padding:30px;">';
        echo '<img src="' . $row['cover'] . '" alt="Avatar" style="width:90px">';
        echo '<div style="padding:30px;font-family:Comic Sans MS;"><span>' .
            $row['title'] . '</span>';
        echo '<div style="margin-top:-7.5%;margin-left:80%;font-size:80%;padding-top: 20px;">' .
            $row['price'] . 'LE</div>';
        echo '<div class="close-btn1"><button class="btn_remove" id="Remove" 
        onClick="RemoveCartItem('. $row['BookID'] . ')">
         <i class="fa fa-close"></i></button></div>';
        echo '</div>';
        echo '</div>';
    }
    echo '<hr width="99%" size="2" color="black"><br><br>';
    if (!empty($rows)) {
        echo '<div class="pr"> Total : <span>' . $rows[0]['Total']
        . '</span> LE </div><br>';
    } else {
        
        echo '<div class="pr"> Total : <span>0
        </span> LE </div><br>';
    }
}


?>
