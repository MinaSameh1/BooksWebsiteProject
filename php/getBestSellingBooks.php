<?php

require_once '../php/database.php';

$sql = "SELECT u.Name AS SellerName, b.BookID, b.cover, b.title, b.author, b.series, b.price,
b.createdDate, COUNT(ci.bookID) AS amountSold
FROM books AS b
INNER JOIN cartitem AS ci ON ci.bookID=b.BookID 
INNER JOIN cart AS c ON ci.CartID = c.CartID
INNER JOIN users AS u ON b.SelerID=u.userID WHERE c.Purchased=1
AND b.isApproved=1
GROUP BY b.BookID ORDER BY amountSold DESC LIMIT 25";

$result = readQuery($pdo, $sql);
while ($row = $result->fetch()) { ?>
    <!-----------slide1---------------->

    <div class="swiper-slide">
        <div class="slider-box">
            <div class="container" style="background-color:transparent;">
                <div class="img-box">
                    <img <?php echo 'src="' . $row['cover'] . '"' ?> style="height:160px; width:145px;">
                    <div class="overlay">
                        <button id="bookID" class="text" onclick="document.getElementById('quickview').style.display='block'" name='quickview' value=<?php echo $row['BookID'] ?>>Quick View</button>
                    </div>
                </div>
            </div><br><br><br>
            <h5 class="price"><?php echo htmlspecialchars($row['title']) ?> </h5>
            <h5 class="price"> Price- <?php echo $row['price'] ?> LE</h5><br>
            <button class="cart" name='CartBut' <?php echo 'onclick="addToCart(\'' . $row['BookID'] . '\')"'; ?>> Add To Cart</button>
        </div>
    </div>
<?php } ?>