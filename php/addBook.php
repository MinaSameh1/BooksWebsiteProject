<?php
/** This file will handle book inserting
 * I won't even touch this file again, too many values
 * we should have splited them up! 
 * */

session_start();
/** 
* Names of Vars
CoverImage
Title
Author
Language
Places
pages
Awards
Awards_num
pdf
Series
Genres
chars
Desc
price
YY
MM
Day
OriPub
*/

require_once 'database.php';
require_once 'page.php';


// First validate all fields!
/*
if (empty($_POST['title']) ) {
    $msg_name = "You must supply the title!";
    header("Location: ../front/BookManagement.php?MSG=$msg_name");
} */

/*
$name_subject = $_POST['title'];
$name_pattern = '/^[a-zA-Z ]*$/';
preg_match($name_pattern, $name_subject, $name_matches);
if (!$name_matches[0]) {
    $msg_name = "Only alphabets and white space allowed";
    header("Location: ../front/BookManagement.php?MSG=$msg_name");
}
*/

if (!isset($_SESSION['userID'])) {
    $msg = "Please login first!";
    header("Location: ../front/BookStore Home.php?MSG=$msg");
}

if (!file_exists($_FILES['myFile']['tmp_name']) || !is_uploaded_file($_FILES['myFile']['tmp_name'])) {
    $msg = "Please Upload Cover Image!";
    header("Location: ../front/BookManagement.php?MSG=$msg");
}

if (!file_exists($_FILES['Choose_File']['tmp_name']) || !is_uploaded_file($_FILES['Choose_File']['tmp_name'])) {
    $msg = "Please Upload PDF!";
    header("Location: ../front/BookManagement.php?MSG=$msg");
}

$CoverFulName = $cover_dir . basename($_FILES["myFile"]["name"]);
$imageFileType = strtolower(pathinfo($CoverFulName, PATHINFO_EXTENSION));

$PdfFulName = $pdf_dir . basename($_FILES["Choose_File"]["name"]);
$pdfFileType = strtolower(pathinfo($PdfFulName, PATHINFO_EXTENSION));


// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
) {
    $msg = "Please Check the Image Format!!";
    header("Location: ../front/BookManagement.php?MSG=$msg");
}

// Allow certain file formats
if ($pdfFileType != "pdf" ) {
    $msg = "Please upload PDF!!!";
    header("Location: ../front/BookManagement.php?MSG=$msg");
}

$day = $_POST['Day'];
$month = $_POST['MM'];
$year = $_POST['YY'];

if (!checkdate($month, $day, $year)) {
    $msg = "Please Check the Publish Date!";
    header("Location: ../front/BookManagement.php?MSG=$msg");
}

$pubDate = strtotime($_POST['MM'] . "/" . $_POST['Day'] . "/" . $_POST['YY']);
$publishDate = date('Y-m-d', $pubDate);

$sql = "
INSERT INTO books ( title, author, total_pages, SelerID, Lang, awards_num, price,
published_date , series, createdDate, original_publish, description)
VALUES ( :title, :author, :total_pages, :selerid, :Lang, :awards_num, :price,
:published_date,  :series, NOW(), :origpub, :desc )";


// Add Tax for website
$price = ($_POST['Price'] * 1.15);
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":title", $_POST['Title']);
$stmt->bindParam(":author", $_POST['Author']);
$stmt->bindParam(":total_pages", $_POST['pages']);
$stmt->bindParam(":selerid", $_SESSION['userID']);
$stmt->bindParam(":Lang", $_POST['Language']);
$stmt->bindParam(":awards_num", $_POST['awards_num']);
$stmt->bindParam(":price", $price);
$stmt->bindParam(":published_date",  $publishDate);
$stmt->bindParam(":series", $_POST['series']);
$stmt->bindParam(":origpub", $_POST['OriPub']);
$stmt->bindParam(":desc", $_POST['Desc']);

$pdo->beginTransaction();
if (!$stmt->execute() ) {
    $msg=var_dump($stmt->errorInfo());
    header("Location: ../front/BookManagement.php?MSG=$msg");
    
};

$BookID = $pdo->lastInsertId();

// Function responsiable for adding characters to the db
// Takes ID and array of string
function addCharacters($db, $chars, $ID)
{
    foreach ($chars as $char) {
        $sql = "INSERT INTO bookscharacters (charactername,bookID)
        VALUES (:chr,:bookid)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(":chr" => $char, ':bookid' => $ID));
    }
    
}

$chars = explode(",", $_POST['chars']);
addCharacters($pdo, $chars, $BookID);

// Function responsiable for adding places to the db
// Takes ID and array of string
function addPlaces($db, $places, $ID)
{
    foreach ($places as $place) {
        $sql = "INSERT INTO booksplaces (place,bookID)
        VALUES (:place,:bookid)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(":place" => $place, ':bookid' => $ID));
    }
    
}

$places = explode(",", $_POST['Places']);
addPlaces($pdo, $places, $BookID);

// Function responsiable for adding awards to the db
// Takes ID and array of string
function addAwards($db, $awards, $ID)
{
    foreach ($awards as $award) {
        $sql = "INSERT INTO booksawards (awardName,bookID)
        VALUES (:award,:bookid)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(":award" => $award, ':bookid' => $ID));
    }
    
}

$Awards = explode(",", $_POST['Awards']);
addAwards($pdo, $Awards, $BookID);


// Book Genre
$Genres = explode(",", $_POST['Genres']);
foreach ($Genres as $genre ) {
    $stmt = $pdo->prepare(
        "SELECT * FROM genres WHERE genreName=:genre"
    );
    $stmt->bindParam(":genre", $genre);
    $stmt->execute();
    $GenreCheck = $stmt->fetch();
    $genreID = 0;
    if ($GenreCheck == false ) {
        // Insert Genre
        $stmt = $pdo->prepare("INSERT INTO genres(genreName) VALUES(:genre)");
        $stmt->execute(array( ":genre" => $genre));
        $genreID = $pdo->lastInsertId();
    } else {
        $genreID = $GenreCheck['genreID'];
    }
    $stmt = $pdo->prepare(
        "INSERT INTO booksgenres VALUES(:BookID, :genreID)"
    );
    $stmt->execute(
        array( ":genreID" => $genreID, ":BookID" => $BookID )
    );
}



$pdfName = $pdf_dir . "pdf" . $BookID . ".pdf";
$pdfLocation = "pdf/pdf" . $BookID . ".pdf";

$coverName = $cover_dir . "cover" . $BookID . "." . $imageFileType;
$coverLocation = "covers/cover" . $BookID . "." . $imageFileType;


$stmt = $pdo->prepare(
    "UPDATE books SET cover=:cover WHERE  BookID=:BookID"
);

$stmt->bindParam(":BookID", $BookID);
$stmt->bindParam(":cover", $coverLocation);
$stmt->execute();

$stmt = $pdo->prepare(
    "INSERT INTO pdf(bookID, pdfLocation) VALUES(:book, :pdf)"
);
$stmt->bindParam(":book", $BookID);
$stmt->bindParam(":pdf", $pdfLocation);
$stmt->execute();
$pdo->commit();

move_uploaded_file($_FILES['myFile']['tmp_name'], $coverName);
move_uploaded_file($_FILES['Choose_File']['tmp_name'], $pdfName);


if ($_SESSION['Admin']) {
    header("Location: ../front/Book1.php");
} else {
    header("Location: ../front/Profile.php");
}
