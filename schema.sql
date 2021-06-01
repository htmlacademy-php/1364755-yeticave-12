CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  category_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(30),
  code VARCHAR(10)
);

CREATE TABLE lots (
  lot_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  date_add DATETIME,
  name VARCHAR(30),
  description TEXT(100),
  img VARCHAR(50),
  starting_price INT,
  date_end DATETIME,
  bet_step INT,
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(user_id),
  FOREIGN KEY(category_id) REFERENCES categories(category_id)
);

CREATE TABLE bets (
  bet_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  date_add DATETIME,
  sum INT,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(user_id),
  FOREIGN KEY(lot_id) REFERENCES lots(lot_id)
);

CREATE TABLE users (
  user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  registration_date DATETIME,
  email VARCHAR(50) UNIQUE,
  name VARCHAR(30),
  password VARCHAR(20) UNIQUE,
  contacts TEXT(100),
  lot_id INT NOT NULL,
  bet_id INT NOT NULL,
  FOREIGN KEY(lot_id) REFERENCES lots(lot_id),
  FOREIGN KEY(bet_id) REFERENCES bets(bet_id)
);
