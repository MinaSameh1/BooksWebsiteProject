USE project;

CREATE TABLE Books(
  BookID        INT AUTO_INCREMENT NOT NULL,
  title          VARCHAR(255) NOT NULL,
  author 		VARCHAR(255),
  total_pages    INT NULL,
  SelerID		 INT,
  Lang varchar(50) DEFAULT NULL,
  awards_num INT(11) DEFAULT NULL,
  price DOUBLE(4,2),
  published_date DATE NULL,
  -- Book type if 0 means soft if 1 means HARD
  bookType INT,
  series varchar(200) DEFAULT NULL,
  original_publish varchar(10) DEFAULT NULL,
  cover varchar(20) DEFAULT NULL,
  description text DEFAULT NULL,
  PRIMARY KEY(BookID),
  CONSTRAINT FK_SelerID FOREIGN KEY (SelerID) REFERENCES users(userID)
);

CREATE TABLE genres(
	genreID INT AUTO_INCREMENT NOT NULL,
	genreName VARCHAR(255) NOT NULL,
	PRIMARY KEY(genreID)
);

CREATE TABLE BooksGenres(
	bookID INT,
	genreID INT,
	CONSTRAINT FK_genreID FOREIGN KEY (genreID) REFERENCES genres(genreID),
	CONSTRAINT FK_genresbookID FOREIGN KEY (bookID) REFERENCES Books(BookID)
);


CREATE TABLE BooksAwards(
	awardName TEXT,
	bookID INT,
	CONSTRAINT FK_awardsbookID FOREIGN KEY (bookID) REFERENCES Books(BookID)
);


CREATE TABLE BookPlaces(
	place TEXT,
	bookID INT,
	CONSTRAINT FK_placesbookID FOREIGN KEY (bookID) REFERENCES Books(BookID)
);

CREATE TABLE BooksCharacters(
	characterName TEXT,
	bookID INT,
	CONSTRAINT FK_charactersbookID FOREIGN KEY (bookID) REFERENCES Books(BookID)
);