DROP TABLE RFQDetail;
DROP TABLE RFQ;
DROP TABLE Inventory;
DROP TABLE ShippingAddress;
DROP TABLE BillingAddress;
DROP TABLE Rep;
DROP TABLE Manager;
DROP TABLE CustomerAccount;

CREATE TABLE CustomerAccount( 
	AccountNumber INTEGER AUTO_INCREMENT NOT NULL, 
	CompanyName VARCHAR(99) NOT NULL, 
	QuoteType VARCHAR(20) NOT NULL, 
	Comments TEXT,
	UNIQUE(CompanyName),
	PRIMARY KEY(AccountNumber)
);

CREATE TABLE ShippingAddress( 
	ShippingID INTEGER NOT NULL AUTO_INCREMENT,
	AccountNumber INTEGER NOT NULL,
	Street VARCHAR(50) NOT NULL, 
	City VARCHAR(20) NOT NULL, 
	State VARCHAR(3) NOT NULL, 
	Zip INTEGER NOT NULL,
	PRIMARY KEY(ShippingID),
	FOREIGN KEY(AccountNumber) REFERENCES CustomerAccount(AccountNumber)
);

CREATE TABLE BillingAddress( 
	BillingID INTEGER NOT NULL AUTO_INCREMENT,
	AccountNumber INTEGER NOT NULL,
	Street VARCHAR(50) NOT NULL, 
	City VARCHAR(20) NOT NULL, 
	State VARCHAR(3) NOT NULL, 
	Zip INTEGER NOT NULL,
	PRIMARY KEY(BillingID),
	FOREIGN KEY(AccountNumber) REFERENCES CustomerAccount(AccountNumber)
);

CREATE TABLE Rep( 
	RepID INTEGER NOT NULL AUTO_INCREMENT, 
	FirstName VARCHAR(20) NOT NULL, 
	LastName VARCHAR(20) NOT NULL, 
	Email VARCHAR(30) NOT NULL,
	Password VARCHAR(30) NOT NULL, 
	Phone VARCHAR(14) NOT NULL, 
	AccountNumber INTEGER NOT NULL, 
	PRIMARY KEY(RepID), 
	FOREIGN KEY(AccountNumber) REFERENCES CustomerAccount(AccountNumber)
);

 CREATE TABLE Manager( 
	 ManagerID INTEGER NOT NULL AUTO_INCREMENT, 
	 FirstName VARCHAR(20) NOT NULL, 
	 LastName VARCHAR(20) NOT NULL,  
	 Email VARCHAR(30) NOT NULL,
	 Password VARCHAR(30) NOT NULL,
	 Phone VARCHAR(14) NOT NULL, 
	 PRIMARY KEY(ManagerID)
 );

CREATE TABLE Inventory( 
	PartID INTEGER AUTO_INCREMENT NOT NULL, 
	Name VARCHAR(20) NOT NULL, 
	Price FLOAT(6, 2) NOT NULL, 
	Quantity INTEGER NOT NULL, 
	Description VARCHAR(150), 
	Manufacturer VARCHAR(20) NOT NULL, 
	Comments VARCHAR(150), 
	PRIMARY KEY(PartID)
);

CREATE TABLE RFQ(
	RFQID INTEGER AUTO_INCREMENT NOT NULL,
	AccountNumber INTEGER NOT NULL,
	PRIMARY KEY(RFQID),
	FOREIGN KEY (AccountNumber) REFERENCES CustomerAccount(AccountNumber)
);

CREATE TABLE RFQDetail(
	RFQDetailID INTEGER AUTO_INCREMENT NOT NULL,
	RFQID INTEGER NOT NULL,
	PartID INTEGER NOT NULL,
	Quantity INTEGER NOT NULL,
	DateRequired DATE NOT NULL,
	PRIMARY KEY(RFQDetailID),
	FOREIGN KEY (RFQID) REFERENCES RFQ(RFQID),
	FOREIGN KEY (PartID) REFERENCES Inventory(PartID)
);



