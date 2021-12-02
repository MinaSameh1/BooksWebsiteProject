<?php
require_once('database.php');

$sql = "SELECT * FROM cart WHERE userID=1 AND Purchased=1";
$result = readQuery($pdo, $sql);
$rows = $result->fetchAll();

foreach( $rows as $row ) {
    echo "<tr>";
    $sql = "SELECT bookID,price FROM cartitem WHERE CartID=" . $row['CartID'];
    $result = readQuery($pdo, $sql);
    $CartRows = $result->fetchAll();
    foreach($CartRows as $Item) {
        $sql = "SELECT title,author,series,selerID FROM books WHERE BookID=" . $Item['bookID'];
        $result = readQuery($pdo, $sql);
        $Book = $result->fetch();
        var_dump($Book['series']);
    }
    echo "</tr>";
}