# Books Website Project

This was a group effort in which I handled only the Backend and database, these are the files I created.
Since I didn't create any of the HTML,CSS and JS content I won't share it publicly, however I did create a new page that displayed
the books using cards template from W3Schools and reusing code from other pages that I will share, I did use jQuery to help with AJAX.       

### This Repo Won't Have any Frontend Pages except 1 As I didn't work on them!!!!

The entire website was done in only two weeks,
and luckily I studied PHP, Python and SQL before that.

*Note: the JS wasn't compressed for readability.*     

I consider this project a success as it was a learning project that I learned a lot from it, including but not limited to:


---


## Databases

I personally used MariaDB as it was faster for me, and lighttpd server instead of Apache (Wanted a change nothing more and it being a 
tiny bit quicker was a tiny plus :D ).      


This Database I am really proud of it, I believe I did it well, if I had to change something about it I would have changed 
authors and got them out of the same table as books.     

I am really Happy about this code snippet, it took me a large amount of time to make sure it was the best way I could do it, and its a __Many to One__ relationship between the cartItem and cart.

[](https://github.com/MinaSameh1/BookWebsiteGradProject/blob/main/img/cart.png)

Of course having many tables lead to lines such as these x(   
   
```php
	
	// SQL Query to get the items just as we want them :)               
	$sql = "
	SELECT u.userID, b.BookID, b.cover, b.title, ci.price, 
	(SELECT SUM(price) FROM cartitem WHERE CartID=:CartID ) AS 
	Total FROM cart AS c 
	INNER JOIN cartitem AS ci ON ci.CartID=c.CartID
	INNER JOIN books AS b ON b.BookID=ci.bookID 
	INNER JOIN users AS u ON c.userID=u.userID
	WHERE ci.CartID=:CarttID AND u.userID=:userID AND c.Purchased=0";

```

--- 

## Python And Scripts
[GoodreadsScraper](https://github.com/havanagrawal/GoodreadsScraper) was used to get a dataset
containing real data of books, as seen in this [file](https://github.com/MinaSameh1/BookWebsiteGradProject/blob/main/book_best_001_025.jl)
containing the dataset we used.                                               

I also created a script that created 6000 users with names, passwords(encrypted using SHA256 which is not the best way to do it but I didnt want to keep track of a key acrross multiple people and wanted it to look "real"), emails, etc using Faker to test the database and they all work and sign in.                    
*note: dont tell anyone but their passwords are 1234, just a secret between you and me ;), what an unexpected consiendance for 6000 totally real people to have the same password huh*

I have created scripts using selenium and requests to download cover images and descriptions using the URL from a json file,
so if the dataset changes we can still get the description of the data and its images, sadly this process takes some time 
depending on the internet speed and the [scripts](https://github.com/MinaSameh1/BookWebsiteGradProject/tree/main/Python%20Scripts) 
 written isn't my best work and aren't that well optimized, however that is fine as the [scripts](https://github.com/MinaSameh1/BookWebsiteGradProject/tree/main/Python%20Scripts) 
are designed to just run once get data put in a MySQL DB  and aren't gonna be used again.                      

Another Script was created to move data from the jl file to database in mysql.

---

## Php 
The personal HomePage language, the one language in which its `strlen` returns 
bytes not actual length, it was a fun language to tinker with.

I wanted to use MVC, due to obvious time limitations I couldn't, I did try however to stick to the [coding style](https://www.php-fig.org/psr/psr-12/).

I did use PDO instead of mysqli, and a teammate of mine made the admin page using mysqli, which is again a mess but that is fine as its a learning Project :) 

I learned somethings about UI and UX, and PHP was a wild ride.

Also I did learn a bit of JQuery to be used with AJAX.

Code snippets PHP:

```php
    // First get Cart ID of user
    $result = readQuery(
        $pdo,
        "SELECT * FROM cart WHERE userID=" . $_SESSION['userID'] . " AND Purchased=0"
    );
    $row = $result->fetch();
    // Add item to cartitem table
    // To do that get price
    $result = readQuery(
        $pdo,
        "SELECT price FROM books WHERE BookID=" . $BookID
    );
    $Price_row = $result->fetch();

    // Then insert into cartitem
    $sql = "INSERT INTO cartitem ( BookID, CartID, price)
    VALUES ( :BookID, :CartID, :price )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":BookID", $BookID);
    $stmt->bindParam(":CartID", $row['CartID']);
    $stmt->bindParam(":price", $Price_row['price']);
    $stmt->execute();

    // Then Update the date in the cart 
    $stmt = $pdo->prepare(
        "UPDATE cart SET CreatedDate=NOW() WHERE userID=:userID AND Purchased=0"
    );
    $stmt->bindParam(":userID", $_SESSION['userID']);
    $stmt->execute();
    $pdo->commit();
    echo "SUCCESS Item added to Cart!";
```

--- 

### Ajax 

The website uses AJAX (implemented by me) to display books as the user scrolls, so it doesn't load all the books and waste his time and data and the server time,
it also allows for one to search dynamically using name or price or genre or both price and name, or both price and genre.        

We also use AJAX in quickView, which is a button upon clicked a pop up that shows info about the book shows without refreshing the page,
Also in the cart page, updated the page right away without refreshs and deleted the item from cart in DB or added it depending on the action.

code snippets AJAX and jQuery:

```js
            function quickView(e) {
                // Save value of BookID in var
                var id = event.target.value;
                $.ajax({
                    // Send ID to the PHP file using GET
                    method: "GET",
                    url: "../php/quickView.php",
                    data: {
                        bookid: id
                    },
                    dataType: "html", //expect html to be returned 
                    // On success put the HTML 
                    success: function(responseObject) {
                        $(".form2").html(responseObject);
                    },
                    failure: function() {
                        alert('failed to get Data!');
                    }
                });
            }

            // On Button Click do the request
            $('.text').click(function(e) {
                e.preventDefault();
                quickView(e);
            });

```

