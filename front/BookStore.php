<?php
session_start();
?>

<!Doctype html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> BookStore Management Books </title>
    <!---------------- Page Icon --------------------->
    <link rel="shortcut icon" href="Icon3.jpeg">

    <link rel="stylesheet" href="swiper.min.css" />
    <link rel="stylesheet" href="BookStore Books.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="Quik View.css">
    <link rel="stylesheet" href="select.css">
    <link rel="stylesheet" href="Cart.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <style>
        .sec3 {
            overflow: hidden;
            transition: 0.1s;
            position: fixed;
            margin-top: -20px;
            z-index: 99;
        }
    </style>
    <script>
        $(document).ready(function() {
            getBooks();
            $(window).scroll(function() {
                if (parseInt($(window).scrollTop()) == $(document).height() - $(window).height()) {
                    getBooks();
                }
            });

			// On Input text typed Search
            $(".search").on("input", function() {
                search();
            });

        });

        function QuickViewClickListener() {
            // On Button Click do the request
            $(".text").click(function(e) {
                e.preventDefault();
                quickView(e);
            });
        }

        function quickView(e) {
            // Save value of BookID in var
            var id = event.target.value;
            $.ajax({
                // Send ID to the PHP file using GET
                method: "GET",
                data: {
                    bookid: id
                },
                url: "../php/quickView.php",
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


        function search() {
            SearchWord = $(".search").val();
            if (SearchWord != "") {
                SearchBool = true;
				$(window).scrollTop(0);
                rowStart = 0;
                rowLimit = 8;
                $("#BOOKS").html("");
                setTimeout(
                    getBooks(),
                    600
                );
            } else {
                SearchBool = false;
				$(window).scrollTop(0);
                rowStart = 0;
                rowLimit = 8;
                $("#BOOKS").html("");
                setTimeout(
                    getBooks(),
                    600
                );
            }
        }


        // MinPrice max price
        var min, max;
        var Timer = 0;
        // How many items to be shown
        var rowStart = 0;
        var rowLimit = 8;
        // If reached end of query
        var reachedMax = false;
        // if there is a search
        var SearchBool = false;
        // The search word
        var SearchWord = "";
        var Genre = "";
		// Search By Price or clear
		var SearchByPriceBool = false;
		
        function FilterPrice() {
            rowStart = 0;
            rowLimit = 8;
			$(window).scrollTop(0);
            $("#BOOKS").html("");
			SearchByPriceBool = true;
            setTimeout(
                getBooks(),
                500
            );
        }

        $(".FilterGenre").live('change', function() {
            Genre = $(this).val();
            if (Genre == "0") {
                rowStart = 0;
                rowLimit = 8;
            }
			$(window).scrollTop(0);
            $("#BOOKS").html("");
            setTimeout(
                getBooks(),
                500
            );
        });

		// Resets the Price sorting
		function ClearPriceSort() {
			SearchByPriceBool = false;
			$(window).scrollTop(0);
			// Reset The page
			rowStart = 0;
			rowLimit = 8;
            $("#BOOKS").html("");
            setTimeout(
                getBooks(),
                500
            );
		}
		
        function getBooks() {
            if (reachedMax) {
                return;
            }
            $.ajax({
                method: "GET",
                data: {
                    start: rowStart,
                    limit: rowLimit,
                    search: SearchWord,
                    priceMin: min,
                    priceMax: max,
                    genreID: Genre,
					searchByPrice: SearchByPriceBool
					},
                url: "../php/getBooks.php",
                dataType: "html",
                success: function(response) {
                    if (response == "reachedMax") {
                        reachedMax = true;
                        return;
                    } else if (response == "notFound") {
                        $("#BOOKS").html("Book Not Found!");
                    }
                    rowStart += rowLimit;
                    $("#BOOKS").append(response);
                    QuickViewClickListener();
                },
                failure: function() {
                    alert('failed to get Data!');
                }
            });
        }

        // Removes item From Cart
        function RemoveCartItem(bookid) {
            if (confirm("Remove this item from cart?")) {

                var userid =
                    <?php echo (isset($_SESSION['userID'])) ?
                        $_SESSION['userID'] : 0; ?>;
                $.ajax({
                    method: "GET",
                    data: {
                        BookID: bookid,
                        userID: userid
                    },
                    url: "../php/removeCartItem.php",
                    success: function(MSG) {
                        getCartItems();
                        if (!MSG === "false") {
                            alert(MSG);
                        }
                    },
                    failure: function() {
                        alert("Failed To Remove Item!");
                    }
                });
            }
        }

        // Add book to cart
        function addToCart(SelectedBookID) {
            $.ajax({
                type: 'GET',
                url: '../php/addToCart.php',
                // ID of Book
                data: {
                    BookID: SelectedBookID
                },
                dataType: "html",
                success: function(response) {
                    // If Cart is open then reshow its items!
                    var width = document.getElementById("mySidepanel").style.width;
                    if (width === "450px") {
                        getCartItems();
                    }
                    alert(response);
                }
            });
        }


        function openNav() {
            // If Nav is open then hide it, else show cart items
            if (document.getElementById("mySidepanel").style.width === "450px") {
                closeNav();
            } else {
                document.getElementById("mySidepanel").style.width = "450px";
                getCartItems();
            }
        }
        // Get Cart Items and show them in the Nav
        function getCartItems() {
            var id = <?php echo (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0; ?>;
            $.ajax({
                method: "GET",
                url: "../php/getCartItems.php",
                data: {
                    userID: id
                },
                dataType: "html", //expect html to be returned 
                // On success put the HTML 
                success: function(responseObject) {
                    $("#CartItems").html(responseObject);
                },
                failure: function() {
                    alert('failed to get Data!');
                }
            });
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
    </script>
    <style>
        .btn_remove {
            background-color: DodgerBlue;
            /* Blue background */
            border: none;
            /* Remove borders */
            color: white;
            /* White text */
            padding: 6px 10px;
            /* Some padding */
            font-size: 10px;
            /* Set a font size */
            cursor: pointer;
            /* Mouse pointer on hover */
        }

        /* Darker background on mouse-over */
        .btn_remove:hover {
            background-color: RoyalBlue;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            max-width: 250px;
            margin: auto;
            text-align: center;
            font-family: arial;
        }

        .price {
            color: grey;
            font-size: 22px;
        }

        .card button {
            border: none;
            outline: 0;
            padding: 12px;
            color: white;
            background-color: #000;
            text-align: center;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        .card button:hover {
            opacity: 0.7;
        }

        /* Float four columns side by side */
        .column {
            float: left;
            width: 20%;
            padding: 0 10px;
        }

        /* Remove extra left and right margins, due to padding in columns */
        .row {
            margin: 0 -5px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Style the counter cards */
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            /* this adds the "card" effect */
            padding: 16px;
            text-align: center;
            background-color: #f1f1f1;
        }

        /* Responsive columns - one column layout (vertical) on small screens */
        @media screen and (max-width: 600px) {
            .column {
                width: 100%;
                display: block;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <section class="header">
        <nav>
            <h3 class="Logo"><B class="Log"><I>J</I></B>ust <B class="Log"><I>B</I></B>ooks</h3>
            <div class="nav-links" id="navLinks">
                <ul>
                    <li><a href='BookStore Home.php'>Home</a></li>
                    <li><a href='About US.html'>About US</a></li>
                    <li><a href='#AllBooks'>BookStore</a></li>
                    <?php if (isset($_SESSION["logged_in"])) { ?>
                        <li><a href='Profile.php'>Profile</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION["logged_in"])) { ?>
                        <li><a href="../php/logout.php"><button class='loginbtn' style="width:auto"><img src='logout.png'>Logout</a></li>
                    <?php } else { ?>
                        <li><button class='loginbtn' onclick="document.getElementById('login-form').style.display='block'" style="width:auto;"><i class="fa fa-fw fa-user"></i>Login</button></li>
                    <?php } ?>
                    <li><button onclick="openNav()"><i class="fa fa-cart-plus"></i></button></li>
                </ul>
            </div>
        </nav>

    </section>

    <div class="full-page">
        <!---------------------------------------------End Nav Bar ----------------------------------------->




        <!----------------------------------------- Form Login ----------------------------------->
        <div id='login-form' class='login-page'>
            <section class="form">

                <!-------- Buttom Close -------->
                <span onclick="document.getElementById('login-form').style.display='none'" class="close-btn fas fa-times" title="close"></span>

                <div class="form-box">

                    <!---------------------------------- Button Login & Register------------------------------>
                    <img src="image4.jpeg" class="iimage">
                    <div class='button-box'>
                        <div id='btn'></div>
                        <button type='button' onclick='login()' class='toggle-btn'>Log In</button>
                        <button type='button' onclick='register()' class='toggle-btn'>Register</button>
                    </div>
                    <!---------------------------------- Button Login & Register------------------------------>

                    <!---------------------------------- Form Login ------------------------------>

                    <form id='login' class='input-group-login' method="post" action=../php/login.php>

                        <div class="ligicon">
                            <img src="App-login-manager-icon.png" class="imglogin">
                        </div>
                        <br>

                        <input name='Login' type='text' class='input-field' placeholder='UserName or Email' required>
                        <input name='Pass' type='password' class='input-field' placeholder='Enter Password' required>
                        <button type='submit' class='submit-btn' style="margin-left:60px;margin-top:10px;"><B>Log in</B></button>
                    </form>
                    <!----------------------------------End Form Login ------------------------------>

                    <!---------------------------------- Form register ------------------------------>

                    <form id='register' class='input-group-register' method="post" action="../php/register.php">

                        <!--------------- Multi Select Checkbox ------------->
                        <div class="containeer">
                            <h4> Choose your hobbies</h4>

                            <div>
                                <label>
                                    <input type="checkbox" name="">

                                    <span>Fantasy</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span>Drama</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span>Action</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span>Mystery</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span>Horror</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span>Thriller</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span>Fiction</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span style="padding:7px 8px;">Romance</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span style="padding:7px 7px;">Classics</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span style="padding:7px 20px;">Supernature</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span style="margin-left:-5px;">Science Fiction</span>
                                </label>
                            </div>

                            <div>
                                <label>
                                    <input type="checkbox" name="">
                                    <span style="padding:7px 20px; margin-left:75px;">Adventure</span>
                                </label>
                            </div>
                        </div>
                        <!---------------End  Multi Select Checkbox ------------->

                        <div class="reg">

                            <input type='text' class='input-field' placeholder='User Name ' required style="width: 100%;">
                            <input type='password' class='input-field' placeholder='Enter Password' required style="width: 100%;">
                            <input type='password' class='input-field' placeholder='Confirm Password' required style="width: 100%;">
                            <input type='text' class='input-field' placeholder='Name' required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="span"><input type='email' class='input-field' placeholder='Email' required></span>
                            <input type='Phone' class='input-field' placeholder='Phone Number' required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="span"><input type='age' class='input-field' placeholder='Age' required></span>
                            <input type="date" name="Date" class='input-field' placeholder='Day Of Birtday' required style="width: 100%;"><br>
                            <input type='checkbox' class='check-box'><span class="Spann">I agree to the terms and conditions</span>
                            <button type='submit' class='submit-btn' style="margin-left:160px; width:100%;"><B>Register<B></button>
                        </div>
                    </form>
                    <!----------------------------------End Form register ------------------------------>

                </div>
            </section>
        </div>
    </div>

    <script>
        var x = document.getElementById('login');
        var y = document.getElementById('register');
        var z = document.getElementById('btn');

        function register() {
            x.style.left = '-400px';
            y.style.left = '55px';
            z.style.left = '150px';
        }

        function login() {
            x.style.left = '150px';
            y.style.left = '950px';
            z.style.left = '0px';
        }
    </script>
    <script>
        var modal = document.getElementById('login-form');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <!------------------------------------------------------------- End Form Login --------------------------------------------------------->

    <br><br><br><br><br><br><br>

    <p id="AllBooks" class="P"> Our</p>
    <p class="P"> BOOK STOR </p>
    <br>
    <h3 class="h3">
        <input type="search" name="Search" placeholder="Search.." class="search">
        <button class="search__submit" aria-label="submit search" onclick="search()"><i class="fa fa-search"></i></button>
    </h3>

    <!--------------- Filter-------------->

    <section class="sec3">

        <div class="border">
            <p class="filter"><B><I> Filter by</I> </B></p><br>
        </div><br><br>
        <!-- Accordion Heading One -->
        <div class="parent-tab">
            <input type="radio" name="tab" id="tab-1">
            <label for="tab-1">
                <span>Collection</span>
                <div class="icon"><i class="fas fa-plus"></i></div>
            </label><br>
            <div id="GenreFilter" class="content">
                <?php
                require_once '../php/database.php';

                // Get Main Genres
                $stmt = $pdo->prepare("SELECT * FROM genres WHERE isMainGenre=1");
                $stmt->execute();
                $genres = $stmt->fetchAll();

                ?>
                <select class="FilterGenre" ID="FilterGenre">
                    <option value="0" default>None</option>
                    <?php
                    foreach ($genres as $genre) { ?>
                        <option value="<?php echo $genre['genreID'] ?>"> <?php echo $genre['genreName'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div><br>
        <div class="border"></div>
        <div class="border">
            <br>
            <!-- Accordion Heading Two -->
            <div class="parent-tab">
                <input type="radio" name="tab" id="tab-2" checked>
                <label for="tab-2">
                    <span>Price</span>
                    <div class="icon"><i class="fas fa-plus"></i></div>
                </label><br>
                <div class="content1">
                    <?php
                    $sqlPrice = "SELECT price FROM books WHERE isApproved=1 ORDER BY price DESC limit 1";
                    $MaxPriceQuery = readQuery($pdo, $sqlPrice);
                    $MaxPriceResult = $MaxPriceQuery->fetch();
                    $MaxPrice = $MaxPriceResult['price'];
                    $sqlPrice = "SELECT price FROM books WHERE isApproved=1 ORDER BY price limit 1";
                    $MinPriceQuery = readQuery($pdo, $sqlPrice);
                    $MinPriceResult = $MinPriceQuery->fetch();
                    $MinPrice = $MinPriceResult['price'];
                    ?>

                    <input id="PriceInput" class="min" name=" range_1" type="range" min="<?php echo $MinPrice ?>" max="<?php echo $MaxPrice ?>" value="1" />
                    <input id="PriceInput" class="max" name=" range_1" type="range" min="<?php echo $MinPrice ?>" max="<?php echo $MaxPrice ?>" value="50" />
                    <span class="range_min light left"><?php echo $MinPrice ?> LE</span>
                    <span class="range_max light right"><?php echo $MaxPrice ?> LE</span>

                </div>
            </div>
            <br><br><br>
        </div>
		<button onclick='ClearPriceSort();' style="margin-top:50px;">Clear Price Sort</button>

    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        (function() {

            function addSeparator(nStr) {
                nStr += '';
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + '.' + '$2');
                }
                return x1 + x2;
            }

            function rangeInputChangeEventHandler(e) {
                var rangeGroup = $(this).attr('name'),
                    minBtn = $(this).parent().children('.min'),
                    maxBtn = $(this).parent().children('.max'),
                    range_min = $(this).parent().children('.range_min'),
                    range_max = $(this).parent().children('.range_max'),
                    minVal = parseInt($(minBtn).val()),
                    maxVal = parseInt($(maxBtn).val()),
                    origin = $(this).context.className;

                if (origin === 'min' && minVal > maxVal - 5) {
                    $(minBtn).val(maxVal - 5);
                }
                var minVal = parseInt($(minBtn).val());
                min = minVal;
                $(range_min).html(addSeparator(minVal * 950) + ' LE');


                if (origin === 'max' && maxVal - 5 < minVal) {
                    $(maxBtn).val(5 + minVal);
                }
                var maxVal = parseInt($(maxBtn).val());
                max = maxVal;
                $(range_max).html(addSeparator(maxVal * 1050) + ' LE');
                FilterPrice();
            }

            $('input[type="range"]').on('input', rangeInputChangeEventHandler);
        })();
    </script>
    <!--------------- End Filter-------------->
    <section class="sec2">
        <div class="row" id="BOOKS">
        </div>
    </section>
    <br><br>

    <!---------------------------------- End Section Books-------------------------------->

    <br><br><br>

    <!------------------- Quick View ----------------------->
    <div id='quickview'>
        <div class="form">
            <span onclick="document.getElementById('quickview').style.display='none'" class="close-btn fas fa-times" title="close"></span>
            <div class="form2">
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById('quickview');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <!------------------- End Quick View ----------------------->

    <br>

    <div id="mySidepanel" class="sidepanel">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <div>
            <div style="font-size: 20px;font-family:Comic Sans MS ;margin-left:40% ;color:#0052B4;"><b>Card Details</b></div> <br>
            <pre style="font-family:Comic Sans MS;margin-left:8%;font-size:90%;color:#000 ;">    #Cover        #Book name	   #Price</pre>
            <div id="CartItems">

            </div>
            <a href="payment.php"><input type="submit" value="Checkout" class="b2"></a>
        </div>

    </div>
    <script type="text/javascript" src="swiper.min.js"></script>
    <script src="script.js"></script>


</body>

</html>
