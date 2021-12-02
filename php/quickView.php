<?php

// Database info
require_once 'database.php';

// The BookID that we will search with
$bookID = json_decode($_GET['bookid']);
// Remove the ID now
unset($_GET['bookid']);
// SQL Query 
$sql = "SELECT * FROM books WHERE BookID=$bookID";
// Read the query 
$result = readQuery($pdo, $sql);
// fetch the result
$row = $result->fetch();

// echo the results
echo '<br><img src="' . $row['cover'] . 
'"align="left" style="width:50%;padding-right: 50px;" class="quick-image"';
echo "<br><br><br>";
echo "<h1>" . $row['title'] . "</h1>";
echo "<br>";
echo "<p> " . $row['price'] . " LE</p><br>\n";
if ($row['series'] != "0" ) {
    echo '<small class="s">' . $row['series'] . '</small>';
}
echo "<br><br><br>";
echo "<input type=\"submit\" onclick=\"addToCart('" . $row['BookID'] . "')\" value='Add To Cart' class=\"add\"> ";
echo "<br><br>";
echo "<a href='ViewMoreDetails.php?id=" . $bookID . 
"' target='_blank' class='a'> View More Details </a>";
