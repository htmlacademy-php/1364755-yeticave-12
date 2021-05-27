CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30),
  code VARCHAR(10)
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add DATETIME,
  name VARCHAR(30),
  description TEXT(100),
  img VARCHAR(50),
  starting_price INT,
  date_end DATETIME,
  bet_step INT
);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add DATETIME,
  sum INT
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registration_date DATETIME,
  email VARCHAR(50) UNIQUE,
  name VARCHAR(30),
  password VARCHAR(20) UNIQUE,
  contacts TEXT(100)
);

SELECT * FROM lots JOIN categories;
SELECT * FROM lots JOIN users;
SELECT * FROM bets JOIN lots;
SELECT * FROM bets JOIN users;
