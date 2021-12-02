<?php

/** This is a file to get books based on intersets */
require_once '../php/database.php';

$str = "  EXPLORE";

// Sql query
if (isset($_SESSION['userID'])) {
    // First check if user has intersets
    $sql = "SELECT * FROM intersets WHERE userID="
        . $_SESSION['userID'];

    $result = readQuery($pdo, $sql);
    $Row = $result->fetch();
    if ($Row == false) {
        $sql = "SELECT * FROM books WHERE isApproved=b'1' ORDER BY RAND() LIMIT 25";
    } else {

        $sql = "SELECT 
b.BookID, b.title, b.author, b.series, b.price, b.cover FROM books AS b 
INNER JOIN booksgenres ON b.bookID = booksgenres.bookID 
INNER JOIN genres ON booksgenres.genreID = genres.genreID
INNER JOIN intersets ON intersets.genreID = genres.genreID 
WHERE intersets.userID=" . $_SESSION['userID'] .
            " AND b.isApproved=b'1' ORDER BY intersets.intersetID LIMIT 30";

        $str = "RECOMMENDED";
    }
} else {
    $sql = "SELECT * FROM books ORDER BY RAND() DESC LIMIT 25";
}

$result = readQuery($pdo, $sql);
$rows = $result->fetchAll();
?>

<label class="recommend"> <?php echo $str ?> </label><br>
<!-- Swiper slide -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php foreach ($rows AS $row ) { ?>
            <!--------slide1-------------->
            <div class="swiper-slide">
                <div class="slider-box">
                    <div class="container">
                        <div class="img-box">
                                    <img <?php echo 'src="' . $row['cover'] . '"' ?> style="height:160px; width:145px;">
                            <div class="overlay">
                                <button id="bookID" class="text" onclick="document.getElementById('quickview').style.display='block'" name='quickview' value=<?php echo $row['BookID'] ?>>Quick View</button>
                            </div>
                        </div>
                            </div><br><br><br>
                    <h5 class="price"> <?php echo $row['title'] ?> </h5>
                    <h5 class="price"> Price- <?php echo $row['price'] ?> LE</h5> <br>
                    <button class="cart" name='CartBut' <?php echo 'onclick="addToCart(\'' . $row['BookID'] . '\')"'; ?>> Add To Cart</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>