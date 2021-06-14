INSERT INTO categories (name, code)
VALUES ('Доски и лыжи', 'boards'),
       ('Крепления', 'attachment'),
       ('Ботинки', 'boots'),
       ('Одежда', 'clothing'),
       ('Инструменты', 'tools'),
       ('Разное', 'other');

INSERT INTO users (registration_date, email, name, password, contacts)
VALUES ('2021-05-26 12:00:00', 'noname@mail.ru', 'Ноунейм', 'qwertyui', 'Телефон: 88005553535'),
       ('2021-01-16 16:00:00', 'luckybet@mail.ru', 'Счастливчик', '12345', 'Телефон: 89537772332');

INSERT INTO lots (date_add, name, description, img, starting_price, date_end, bet_step, user_id, category_id)
VALUES ('2021-06-04 19:08:00', '2014 Rossignol District Snowboard', 'доска заряжена на катку', 'img/lot-1.jpg', '10999',
        '2021-12-07 16:00:00', '100', '2', '1'),
       ('2021-06-04 19:15:00', 'DC Ply Mens 2016/2017 Snowboard', 'попробуй - полюбишь', 'img/lot-2.jpg', '15999',
        '2021-12-07 16:00:00', '100', '1', '1'),
       ('2021-06-04 19:18:00', 'Крепления Union Contact Pro 2015 года размер L/XL', 'небольшой люфт присутствует', 'img/lot-3.jpg', '8000',
        '2021-12-07 16:00:00', '100', '1', '2'),
       ('2021-06-04 19:23:00', 'Ботинки для сноуборда DC Mutiny Charocal', 'в идеальном состоянии, юзал пару раз', 'img/lot-4.jpg', '10999',
        '2021-12-07 16:00:00', '100', '2', '3'),
       ('2021-06-04 19:26:00', 'Куртка для сноуборда DC Mutiny Charocal', 'откатал 1 сезон', 'img/lot-5.jpg', '7500',
        '2021-12-07 16:00:00', '100', '1', '4'),
       ('2021-06-04 19:31:00', 'Маска Oakley Canopy', 'не искривляет представление', 'img/lot-6.jpg', '5400',
        '2021-12-07 16:00:00', '100', '2', '6');

INSERT INTO bets (date_add, sum, user_id, lot_id)
VALUES ('2021-06-04 21:57:00', '11500', '2', '2'),
       ('2021-06-04 22:00:00', '17500', '1', '2');

UPDATE users
SET lot_id = '2',
    bet_id = '2'
WHERE user_id = 1;

UPDATE users
SET lot_id = '1',
    bet_id = '1'
WHERE user_id = 2;

-- получить все категории
SELECT name FROM categories;
-- получить самые новые лоты
SELECT name, c.name, starting_price, img, c.category_id FROM lots l JOIN categories c ON l.category_id = c.category_id ORDER BY date_add DESC;
-- показать лот по его ID
SELECT l.*, c.name FROM lots l JOIN categories c ON l.category_id = c.category_id WHERE lot_id = 2;
-- обновить название лота по его идентификатору
UPDATE lots SET name = 'просто крепления' WHERE	lot_id = 3;
-- получить список ставок для лота по его идентификатору с сортировкой по дате
SELECT * FROM bets WHERE lot_id = 2 ORDER BY date_add DESC;

