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
  date_add DATETIME DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(50),
  category_id INT NOT NULL,
  description TEXT(100),
  img VARCHAR(50),
  starting_price INT,
  bet_step INT,
  date_end DATETIME,
  user_id INT NOT NULL
);

CREATE TABLE bets (
  bet_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  date_add DATETIME,
  sum INT,
  user_id INT NOT NULL,
  lot_id INT NOT NULL
);

CREATE TABLE users (
  user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(50) UNIQUE,
  name VARCHAR(30),
  password VARCHAR(100),
  contacts TEXT(100),
  lot_id INT,
  bet_id INT
);

ALTER TABLE lots ADD FOREIGN KEY(user_id) REFERENCES users(user_id);
ALTER TABLE lots ADD FOREIGN KEY(category_id) REFERENCES categories(category_id);
ALTER TABLE bets ADD FOREIGN KEY(user_id) REFERENCES users(user_id);
ALTER TABLE bets ADD FOREIGN KEY(lot_id) REFERENCES lots(lot_id);
ALTER TABLE users ADD FOREIGN KEY(lot_id) REFERENCES lots(lot_id);
ALTER TABLE users ADD FOREIGN KEY(bet_id) REFERENCES bets(bet_id);

CREATE FULLTEXT INDEX lots_ft_search ON lots(name, description);
