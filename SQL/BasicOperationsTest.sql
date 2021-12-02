-- This is a statment to get Books with their genres
SELECT books.BookID, books.title , genres.genreName FROM books
JOIN booksgenres ON books.bookID = booksgenres.bookID 
JOIN genres ON genres.genreID = booksgenres.genreID WHERE books.BookID = <BOOKID>;

-- Query to get data on books sold by user.
SELECT b.title, b.author, b.series, ci.price, b.createdDate, u.Name, u.phoneNumber
FROM books AS b 
INNER JOIN cartitem AS ci ON ci.bookID = b.BookID 
INNER JOIN cart AS c ON ci.CartID = c.CartID 
INNER JOIN users AS u ON c.userID = u.userID 
WHERE b.SelerID=3313;

-- Get data to be shown to user as his purchase and pdf to download
SELECT b.title, b.author, b.series, u.Name, ci.price, b.published_date, 
c.createdDate,p.pdfLocation 
FROM books AS b
LEFT JOIN pdf AS p ON p.bookID=b.BookID
INNER JOIN cartitem AS ci ON ci.bookID = b.BookID 
INNER JOIN cart AS c ON ci.CartID = c.CartID 
INNER JOIN users AS u ON c.userID = u.userID 
WHERE c.userID=1

-- query to get books Purchased that are owned by user
SELECT b.BookID, b.title, b.author, b.series, b.price,
b.createdDate, COUNT(ci.bookID) AS amountSold FROM books AS b
INNER JOIN cartitem AS ci ON ci.bookID=b.BookID 
INNER JOIN cart AS c ON ci.CartID = c.CartID
WHERE c.Purchased=1 AND b.SelerID=3313

-- Delete GenreID from booksgenres 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='TEXT';
-- Delete Genre Itself, but you first have to delete its foregin key uses.
DELETE genres FROM genres WHERE genreName='TEXT';

-- Get last bookID from seler with title
SELECT BookID FROM BOOKS WHERE SelerID=<ID> AND title=<TITLE> ORDER BY BookID DESC LIMIT 1;

-- Genres that get deleted : 


-- 0 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Egypt';

DELETE genres FROM genres WHERE genreName='Egypt';
-- 1 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Africa';

DELETE genres FROM genres WHERE genreName='Africa';

-- 2 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Israel';

DELETE genres FROM genres WHERE genreName='Israel';

-- 3 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='United States of America';
DELETE genres FROM genres WHERE genreName='United States of America';

-- 4 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='India';
DELETE genres FROM genres WHERE genreName='India';

-- 5 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Holocaust';
DELETE genres FROM genres WHERE genreName='Holocaust';

--6
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Kenya';
DELETE genres FROM genres WHERE genreName='Kenya';

-- 7 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Ukraine';
DELETE genres FROM genres WHERE genreName='Ukraine';

-- 8 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='BDSM';
DELETE genres FROM genres WHERE genreName='BDSM';

-- 9 
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Erotic Romance';
DELETE genres FROM genres WHERE genreName='Erotic Romance';

-- 10

DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Nigeria';
DELETE genres FROM genres WHERE genreName='Nigeria';


-- 10
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Italy';
DELETE genres FROM genres WHERE genreName='Italy';

-- 11
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Roman';
DELETE genres FROM genres WHERE genreName='Roman';

-- 12
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Northern Africa';
DELETE genres FROM genres WHERE genreName='Northern Africa';

-- 13
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Amazon';
DELETE genres FROM genres WHERE genreName='Amazon';

-- 14
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Spain';
DELETE genres FROM genres WHERE genreName='Spain';-- 15

-- 15
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='African American';
DELETE genres FROM genres WHERE genreName='African American';

-- 16
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='China';
DELETE genres FROM genres WHERE genreName='China';

-- 17
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Ireland';
DELETE genres FROM genres WHERE genreName='Ireland';-- 18

-- 18
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='New York';
DELETE genres FROM genres WHERE genreName='New York';

-- 19
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Turkish';
DELETE genres FROM genres WHERE genreName='Turkish';

-- 20
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Western Africa';
DELETE genres FROM genres WHERE genreName='Western Africa';

-- 21
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Jewish';
DELETE genres FROM genres WHERE genreName='Jewish';

-- 22
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Christianity';
DELETE genres FROM genres WHERE genreName='Christianity';

-- 23
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Judaism';
DELETE genres FROM genres WHERE genreName='Judaism';

-- 24
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Christian';
DELETE genres FROM genres WHERE genreName='Christian';

-- 25
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Southern Africa';
DELETE genres FROM genres WHERE genreName='Southern Africa';

-- 26
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='South Africa';
DELETE genres FROM genres WHERE genreName='South Africa';

-- 27
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Greece';
DELETE genres FROM genres WHERE genreName='Greece';

-- 28
DELETE booksgenres
FROM booksgenres inner JOIN genres ON booksgenres.genreID = genres.genreID
WHERE genres.genreName='Australia';
DELETE genres FROM genres WHERE genreName='Australia';