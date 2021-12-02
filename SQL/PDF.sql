CREATE TABLE `pdf` (
	`pdfID` INT NOT NULL AUTO_INCREMENT PRIMARY key,
	`bookID` INT NOT NULL DEFAULT 0,
	`pdfLocation` TEXT(500) NOT NULL DEFAULT '',
	CONSTRAINT `fk_pdfbookID` FOREIGN KEY (`bookID`) REFERENCES `books` (`BookID`)
)
COLLATE='utf8mb4_general_ci'
;
