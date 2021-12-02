CREATE TABLE users(
	userID INT NOT NULL AUTO_INCREMENT,
	userName VARCHAR(17),
	password VARCHAR(255),
	Name VARCHAR(50),
	phoneNumber VARCHAR(20),
	age INT,
	DOB DATE,
	--- If user Type is 0 then he is admin, if 1 a normal user
	UserType INT,
	Email VARCHAR(255),
	Address VARCHAR(50),
	UserBlocked BIT(1),
	PRIMARY KEY(userID)
	);
	
