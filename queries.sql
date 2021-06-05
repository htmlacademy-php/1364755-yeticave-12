INSERT INTO categories
SET name = 'Доски и лыжи', code = 'boards';
INSERT INTO categories
SET name = 'Крепления', code = 'attachment';
INSERT INTO categories
SET name = 'Ботинки', code = 'boots';
INSERT INTO categories
SET name = 'Одежда', code = 'clothing';
INSERT INTO categories
SET name = 'Инструменты', code = 'tools';
INSERT INTO categories
SET name = 'Разное', code = 'other';

INSERT INTO users
SET registration_date = '2021-05-26 12:00:00', email = 'noname@mail.ru', name = 'Ноунейм',
password = 'qwertyui', contacts = 'Телефон: 88005553535';
INSERT INTO users
SET registration_date = '2021-01-16 16:00:00', email = 'luckybet@mail.ru', name = 'Счастливчик',
password = '12345', contacts = 'Телефон: 89537772332';

INSERT INTO lots
SET date_add = '2021-06-04 19:08:00', name = '2014 Rossignol District Snowboard', description = 'доска заряжена на катку',
img = 'img/lot-1.jpg', starting_price = '10999', date_end = '2021-12-07 16:00:00', bet_step = '100', user_id = '2', category_id = '1';
INSERT INTO lots
SET date_add = '2021-06-04 19:15:00', name = 'DC Ply Mens 2016/2017 Snowboard', description = 'попробуй - полюбишь',
img = 'img/lot-2.jpg', starting_price = '15999', date_end = '2021-12-07 16:00:00', bet_step = '100', user_id = '1', category_id = '1';
INSERT INTO lots
SET date_add = '2021-06-04 19:18:00', name = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'небольшой люфт присутствует',
img = 'img/lot-3.jpg', starting_price = '8000', date_end = '2021-12-07 16:00:00', bet_step = '100', user_id = '1', category_id = '2';
INSERT INTO lots
SET date_add = '2021-06-04 19:23:00', name = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'в идеальном состоянии, юзал пару раз',
img = 'img/lot-4.jpg', starting_price = '10999', date_end = '2021-12-07 16:00:00', bet_step = '100', user_id = '2', category_id = '3';
INSERT INTO lots
SET date_add = '2021-06-04 19:26:00', name = 'Куртка для сноуборда DC Mutiny Charocal', description = 'откатал 1 сезон',
img = 'img/lot-5.jpg', starting_price = '7500', date_end = '2021-12-07 16:00:00', bet_step = '100', user_id = '1', category_id = '4';
INSERT INTO lots
SET date_add = '2021-06-04 19:31:00', name = 'Маска Oakley Canopy', description = 'не искривляет представление',
img = 'img/lot-6.jpg', starting_price = '5400', date_end = '2021-12-07 16:00:00', bet_step = '100', user_id = '2', category_id = '6';

INSERT INTO bets
SET date_add = '2021-06-04 21:57:00', sum = '11500', user_id = '2', lot_id = '2';
INSERT INTO bets
SET date_add = '2021-06-04 22:00:00', sum = '17500', user_id = '1', lot_id = '2';

UPDATE users
SET lot_id = '2' WHERE user_id = 1;
UPDATE users
SET bet_id = '2' WHERE user_id = 1;

UPDATE users
SET lot_id = '1' WHERE user_id = 2;
UPDATE users
SET bet_id = '1' WHERE user_id = 2;

-- получить все категории
SELECT name FROM categories;
-- получить самые новые лоты
SELECT l.name, c.name, starting_price, img, c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC;
-- показать лот по его ID
SELECT l.*, c.name FROM lots l JOIN categories c ON l.category_id = c.category_id WHERE lot_id = 2;
-- обновить название лота по его идентификатору
UPDATE lots SET NAME = 'просто крепления' WHERE	lot_id = 3;
-- получить список ставок для лота по его идентификатору с сортировкой по дате
SELECT * FROM bets WHERE lot_id = 2 ORDER BY date_add DESC;

