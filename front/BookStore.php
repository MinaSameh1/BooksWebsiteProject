<?php session_start(); ?>

<!DOCTYPE HTML>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> BookStore Management Home </title>
    <!---------------- Page Icon --------------------->
    <link rel="shortcut icon" href="Icon3.jpeg">

    <link rel="stylesheet" href="swiper.min.css" />
    <link rel="stylesheet" href="BookStore.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
    <link rel="stylesheet" href="Quik View.css">

    <?php
    if (isset($_GET['MSG'])) {
        if (!$_GET['MSG'] === "false") {
            echo "<script>alert('" . $_GET['MSG'] . "');</script>";
        }
    }
    ?>
    <link rel="stylesheet" href="Cart.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
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

        });

        // Removes item From Cart
        function RemoveCartItem(bookid) {
            if (confirm("Remove this item from cart?")) {

                var userid = <?php echo (isset($_SESSION['userID'])) ? $_SESSION['userID'] : 0; ?>;
                $.ajax({
                    method: "GET",
                    url: "../php/removeCartItem.php",
                    data: {
                        BookID: bookid,
                        userID: userid
                    },
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


        // Check if element on Screen!
        function isOnScreen(element) {
            var curPos = element.offset();
            var curTop = curPos.top;
            var screenHeight = $(window).height();
            return (curTop > screenHeight) ? false : true;
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

        var modal = document.getElementById('quickview');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
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
    </style>
</head>

<body>

    <section class="header">
        <nav>
            <h3 class="Logo"><B class="Log"><I>J</I></B>ust <B class="Log"><I>B</I></B>ooks</h3>
            <div class="nav-links" id="navLinks">
                <ul>
                    <li><a href='BookStore.php'>Home</a></li>
                    <li><a href='#'>About</a></li>
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
    <div class="background">
        <div class="mySlides fade">
            <div class="numbertext">1 / 8</div>
            <img src="Background1.jpeg" style="width:100%; height:93vh;">

        </div>

        <div class="mySlides fade">
            <div class="numbertext">2 / 8</div>
            <img src="Background2.jpeg" style="width:100%; height:93vh;">
        </div>

        <div class="mySlides fade">
            <div class="numbertext">3 / 8</div>
            <img src="Background7.jpeg" style="width:100%; height:93vh;">

        </div>

        <div class="mySlides fade">
            <div class="numbertext">4 / 8</div>
            <img src="Background3.jpeg" style="width:100%; height:93vh;">

        </div>

        <div class="mySlides fade">
            <div class="numbertext">5 / 8</div>
            <img src="Background4.jpeg" style="width:100%; height:93vh;">

        </div>

        <div class="mySlides fade">
            <div class="numbertext">6 / 8</div>
            <img src="Background5.jpeg" style="width:100%; height:93vh;">

        </div>

        <div class="mySlides fade">
            <div class="numbertext">7 / 8</div>
            <img src="Background6.jpeg" style="width:100%; height:93vh;">

        </div>

        <div class="mySlides fade">
            <div class="numbertext">8 / 8</div>
            <img src="Background8.jpeg" style="width:100%; height:93vh;">

        </div>

    </div>
    <br>

    <div style="text-align:center">
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>


    <script>
        var slideIndex = 0;
        showSlides();

        function showSlides() {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 100000); // Change image every 2 seconds
        }
    </script>

    <div class="full-page" style="
    height: 100px;
">
        <!-------------------------------------------End Nav Bar ----------------------------------------->




        <!--------------------------------------Form Login ---------------------------------->
        <div id='login-form' class='login-page'>
            <section class="form">

                <!-------- Buttom Close -------->
                <span onclick="document.getElementById('login-form').style.display='none'" class="close-btn fas fa-times" title="close"></span>

                <div class="form-box">

                    <!---------------------------------- Button Login & Register------------------------------>

                    <div class='button-box'>
                        <div id='btn'></div>
                        <button type='button' onclick='login()' class='toggle-btn'>Log In</button>
                        <button type='button' onclick='register()' class='toggle-btn'>Register</button>
                    </div>



                    <form id='login' class='input-group-login' method="post" action=../php/login.php>
                        <input type='text' class='input-field' placeholder='Email Id' required name='Login'>
                        <input type='password' class='input-field' placeholder='Enter Password' required name='Pass'>
                        <input type='checkbox' class='check-box'><span class="Spann">Remember Password</span>
                        <?php if (isset($_SESSION['ERROR'])) { ?>
                            <div><b>Oops</b>, <?php echo $_SESSION['ERROR'];
                                                unset($_SESSION['ERROR']); ?> </div>
                        <?php } ?>
                        <button type='submit' class='submit-btn'><B>Log in</B></button>
                    </form>
                    <?php if (isset($_SESSION['ERROR'])) {
                        echo "<script>alert('" . $_SESSION['ERROR'] . "');</script>";
                    } ?>

                    <form method="post" action="../php/register.php" id='register' class='input-group-register'>
                        <input name="UserName" type='text' class='input-field' placeholder='User Name ' required style="width: 100%;">
                        <input name="Password" type='password' class='input-field' placeholder='Enter Password' required style="width: 100%;">
                        <input name="ConfirmPass" type='password' class='input-field' placeholder='Confirm Password' required style="width: 100%;">
                        <input name="name" type='text' class='input-field' placeholder='Name' required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="span"><input name='email' type='email' class='input-field' placeholder='Email' required></span>
                        <input name="phone" type='Phone' class='input-field' placeholder='Phone Number' required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="span"> <input name='age' type='age' class='input-field' placeholder='Age' required></span>
                        <input name="Date" type="date" class='input-field' placeholder='Day Of Birtday' required style="width: 100%;"><br>
                        <input type='checkbox' class='check-box'><span class="Spann">I agree to the terms and conditions</span>
                        <button type='submit' class='submit-btn'><B>Register</B></button>
                    </form>
                    <!-----------------------------End Form register --------------------------->


                    <script>
                        var x = document.getElementById('login');
                        var y = document.getElementById('register');
                        var z = document.getElementById('btn');

                        function register() {
                            x.style.left = '-420px';
                            y.style.left = '70px';
                            z.style.left = '118px';
                        }

                        function login() {
                            x.style.left = '70px';
                            y.style.left = '470px';
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

                </div>
            </section>
        </div>
    </div>


    <!---------------------------------------------------------- End Form Login ------------------------------------------------------->

    <br><br><br>


    <p id="AllBooks" class="P"> Our</p>
    <p class="P"> BOOK STORE </p>
    <br>
    <h3 class="h3">
        <form method=post action=BookStore.php>
            <input type="search" name="Search" placeholder="Search.." class="search">
            <button class="search__submit" aria-label="submit search" type="sumbit">
                <i class="fa fa-search"></i>
            </button>
        </form>
    </h3>
    <section class="sec2">


        <h3><i>Best Seller </i></h3>

        <div class="swiper-container" style="height: 350px;">
            <div class="swiper-wrapper">

                <?php
                require_once '../php/database.php';
                $sql = "SELECT * FROM Books ORDER BY RAND() LIMIT 25";
                $result = readQuery($pdo, $sql);
                while ($row = $result->fetch()) { ?>
                    <div class="swiper-slide">
                        <div class="slider-box">
                            <div class="container">
                                <div class="img-box">
                                    <img <?php echo 'src="' . $row['cover'] . '"' ?>>
                                    <div class="overlay">
                                        <button id="bookID" class="text" onclick="document.getElementById('quickview').style.display='block'" name='quickview' value=<?php echo $row['BookID'] ?>>Quick View</button>
                                    </div>
                                </div>
                            </div><br>
                            <h4> <?php echo $row['title'] ?> </h4>
                            <h5 class="price"> Price <?php echo $row['price'] ?> LE</h5>
                            <button class="cart" name='CartBut' <?php echo 'onclick="addToCart(\'' . $row['BookID'] . '\')"'; ?>> Add To Cart</button>
                        </div>
                    </div>
                <?php } ?>
                <br><br><br>


            </div>
        </div>
        <h3><i>Explore</i></h3>
        <div class="swiper-container" style="height: 100%;">
            <div class="swiper-wrapper">
                <?php
                require_once '../php/database.php';
                $sql = "SELECT * FROM Books ";
                $result = readQuery($pdo, $sql);
                while ($row = $result->fetch()) { ?>
                    <div class="swiper-slide">
                        <div class="slider-box">
                            <div class="container">
                                <div class="img-box">
                                    <img <?php echo 'src="' . $row['cover'] . '"' ?>>
                                    <div class="overlay">
                                        <button id="bookID" class="text" onclick="document.getElementById('quickview').style.display='block'" name='quickview' value=<?php echo $row['BookID'] ?>>Quick View</button>
                                    </div>
                                </div>
                            </div><br>
                            <h4> <?php echo $row['title'] ?> </h4>
                            <h5 class="price"> Price <?php echo $row['price'] ?> LE</h5>
                            <br>
                            <button class="cart" name='CartBut' <?php echo 'onclick="addToCart(\'' . $row['BookID'] . '\')"'; ?>> Add To Cart</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="sec3">
        <div class="border">
            <p class="filter"><B><I> Filter by</I> </B></p><br>
        </div><br><br>
        <div class="parent-tab">
            <input type="radio" name="tab" id="tab-1">
            <label for="tab-1">
                <span>Collection</span>
                <div class="icon"><i class="fas fa-plus"></i></div>
            </label><br>
            <div class="content">
                <?php
                require_once '../php/database.php';

                // Get Main Genres
                $stmt = $pdo->prepare("SELECT genreName FROM genres WHERE isMainGenre=1");
                $stmt->execute();
                $genres = $stmt->fetchAll();

                foreach ($genres as $genre) {
                    echo '<a href="#" class="filter1">' . "$genre[0]" . '</a><br>';
                }
                ?>

            </div>
        </div><br>
        <div class="border"></div>
        <div class="border">
            <br>

            <div class="parent-tab">
                <input type="radio" name="tab" id="tab-2" checked>
                <label for="tab-2">
                    <span>Price</span>
                    <div class="icon"><i class="fas fa-plus"></i></div>
                </label><br>
                <div class="content1">
                    <input class="min" name="range_1" type="range" min="40" max="400" value="40" />
                    <input class="max" name="range_1" type="range" min="40" max="400" value="200" />
                    <span class="range_min light left">40.000 $</span>
                    <span class="range_max light right">200.000 $</span>


                </div>
            </div>
            <br><br><br>
        </div>
    </section>

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
                $(range_min).html(addSeparator(minVal * 1000) + ' LE');


                if (origin === 'max' && maxVal - 5 < minVal) {
                    $(maxBtn).val(5 + minVal);
                }
                var maxVal = parseInt($(maxBtn).val());
                $(range_max).html(addSeparator(maxVal * 1000) + ' LE');
            }

            $('input[type="range"]').on('input', rangeInputChangeEventHandler);
        })();
    </script>

    <br><br><br>
    <div id='quickview'>
        <div class="form">
            <span onclick="document.getElementById('quickview').style.display='none'" class="close-btn fas fa-times" title="close"></span>

            <div class="form2">

            </div>
        </div>
    </div>

    <div id="mySidepanel" class="sidepanel" style="height:90%; width:0px;">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <div>
            <div style="font-size: 100%;font-family:Comic Sans MS ;margin-left:40% ;color:#0052B4;"><b>Cart Details</b></div> <br>
            <pre style="font-family:Comic Sans MS;margin-left:8%;font-size:90%;color:#000 ;">       #Cover        #Book name	       #Price</pre>
            <div id="CartItems">
            </div>
            <a href="payment.php"><input type="submit" value="Checkout" class="b2"></a>



        </div>

    </div>


    <script>
    </script>



    <script type="text/javascript" src="swiper.min.js"></script>
    <script src="script.js"></script>


    <div class="clearfix"></div>

    <footer> Book Store 2021



</body>

</html>