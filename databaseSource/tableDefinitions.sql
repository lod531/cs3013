CREATE TABLE courseYear
(
 	id int NOT NULL,
	name varchar(255) NOT NULL,
	numericalYear int NOT NULL,
	PRIMARY KEY (id),
	UNIQUE (numericalYear)
);

CREATE TABLE yearModule
(
 	/* name here is expected to be formatted as follows: "cs3013 Such and such" */
 	name varchar(255) NOT NULL,
	courseYearID int NOT NULL,
	PRIMARY KEY (name),
	FOREIGN KEY (courseYearID) REFERENCES courseYear(id)
);


CREATE TABLE user
(
 	username varchar(32) NOT NULL,
	password varchar(64) NOT NULL,
       	usertitle varchar(64) NOT NULL,
	PRIMARY KEY (username)
);	

CREATE TABLE post
(
 	id int NOT NULL,
	dateOfCreation DATETIME NOT NULL,
	lastEdited DATETIME NOT NULL,
	creatorID varchar(32) NOT NULL,
	content varchar(64000) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (creatorID) REFERENCES user(username)
);

CREATE TABLE threads
(
 	id int NOT NULL,
	dateOfCreation DATETIME NOT NULL,
	title varchar(255) NOT NULL,
	parentModuleID varchar(255) NOT NULL,
	featuredAnswerPost int NOT NULL,
	creatorID varchar(32) NOT NULL,
	threadText varchar(64000) NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (parentModuleID) REFERENCES yearModule(name),
	FOREIGN KEY (featuredAnswerPost) REFERENCES post(id),
	FOREIGN KEY (creatorID) REFERENCES user(username)

);

CREATE TABLE moderators
(
 	userID varchar(32) NOT NULL,
	yearModuleName varchar(255) NOT NULL,
	PRIMARY KEY (userID, yearModuleName)
);
