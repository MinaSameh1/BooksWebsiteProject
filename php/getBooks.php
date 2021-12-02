<?php
/** File to display books in BookStore Books.php */
require_once 'database.php';

$i = 0;


if (isset($_GET['searchByPrice'] )) {
	if($_GET['searchByPrice'] === "false") {
		unset($_GET['priceMin']);
	}
}
if ($_GET['search'] != "" ) {

    // First query to get books
    if (isset($_GET['priceMin'])) {
        $sql = "SELECT * FROM books 
WHERE isApproved=1 AND price BETWEEN "
        . $_GET['priceMin'] . " AND " . $_GET['priceMax'] .
            " AND (title LIKE :Search OR 
author LIKE :Search  )
ORDER BY price DESC LIMIT " . $_GET['start'] . "," . $_GET['limit'];
    } else {
        $sql = "SELECT * FROM books WHERE isApproved=1 AND
(title LIKE :Search OR 
author LIKE :Search )
ORDER BY title LIMIT " . $_GET['start'] . "," . $_GET['limit'];
    }

    $stmt = $pdo->prepare($sql);
    $SEARCH = '%' . $_GET['search'] . '%';
    $stmt->execute(array(":Search" => $SEARCH));
    if ($stmt == false
    ) {
        die("reachedMax");
    }
    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        // For each 4 columns create a new row
        $i++;
        if ($i === 4) {
            echo '<div class="row">';
        }
        echo '<div class="column">
                <div class="card">
                    <img src="' . htmlspecialchars($row['cover']) . '" alt="TITLE" style="width:100%">
                    <h1> ' .  htmlspecialchars($row['title']) . '</h1>
                    <p class="price">' .  htmlspecialchars($row['price']) . 'LE</p>
                    <button id="bookid" class="text" onclick="document.getElementById(\'quickview\').style.display=\'block\'" name="quickview" value=' . $row['BookID'] . '>Quick View</button>
                    <p> <button name="CartBut" onclick="addToCart(\'' .  htmlspecialchars($row['BookID']) . '\')">Add to Cart</button></p>
                </div>
            </div>';
        if ($i === 4) {
            echo '</div>';
            $i = 0;
        }
    }
    die();
}

if (!$_GET['genreID'] === "0") {
    if (isset($_GET['priceMin'])) {
        $sql = "SELECT * FROM books AS b 
INNER JOIN booksgenres ON b.bookID = booksgenres.bookID 
INNER JOIN genres ON booksgenres.genreID = genres.genreID
WHERE b.isApproved=1 AND genres.genreID=" . $_GET['genreID'] . " AND BETWEEN "
        . $_GET['priceMin'] . " AND " . $_GET['priceMax'] . " 
LIMIT "
        . $_GET['start'] . "," . $_GET['limit'];
    } else {
        $sql = "SELECT * FROM books AS b 
INNER JOIN booksgenres ON b.bookID = booksgenres.bookID 
INNER JOIN genres ON booksgenres.genreID = genres.genreID
WHERE b.isApproved=1 AND genres.genreID=" . $_GET['genreID'] . " LIMIT "
        . $_GET['start'] . "," . $_GET['limit'];

    }
} else if (isset($_GET['priceMin']) ) {
    $sql = "SELECT * FROM Books
WHERE isApproved=1 AND price BETWEEN "
    . $_GET['priceMin'] . " AND " . $_GET['priceMax'] .
        " ORDER BY price DESC LIMIT " . $_GET['start'] . "," . $_GET['limit'];
} else {
    $sql = "SELECT * FROM Books WHERE isApproved=1
ORDER BY BookID DESC LIMIT " . $_GET['start'] . "," . $_GET['limit'];

}


$stmt = $pdo->query($sql);
if ($stmt == false) {
    die("reachedMax");
}

$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    // For each 4 columns create a new row
    $i++;
    if ($i === 4 ) {
        echo '<div class="row">';
    }
    echo '<div class="column">
                <div class="card">
                    <img src="' . htmlspecialchars($row['cover']) . '" alt="TITLE" style="width:100%">
                    <h1> ' . htmlspecialchars($row['title']) . '</h1>
                    <p class="price">' . htmlspecialchars($row['price']) . 'LE</p>
                    <button id="bookid" class="text" onclick="document.getElementById(\'quickview\').style.display=\'block\'" name="quickview" value=' . $row['BookID'] . '>Quick View</button>
                    <p> <button name="CartBut" onclick="addToCart(\'' . htmlspecialchars($row['BookID']) . '\')">Add to Cart</button></p>
                </div>
            </div>';
    if ($i === 4 ) {
        echo '</div>';
        $i =0;
    }
} 

