-- Предоставление привилегий пользователю на базу данных
GRANT ALL PRIVILEGES ON *.* TO 'user'@'%' WITH GRANT OPTION;

-- Применение изменений
FLUSH PRIVILEGES;

SET NAMES 'utf8';

CREATE TABLE order_status (
  status_id tinyint NOT NULL AUTO_INCREMENT,
  status_name varchar(30) NOT NULL,
  PRIMARY KEY (status_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO order_status (status_name)
values
	('создан'), 					-- 1
	('оплачен'), 					-- 2
	('ожидает получения'), 			-- 3
	('ожидает получения и оплаты'),	-- 4
	('получен'),					-- 5
	('отменен');					-- 6

CREATE TABLE city (
  city_id int NOT NULL AUTO_INCREMENT,
  city_name varchar(25) NOT NULL,
  PRIMARY KEY (city_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO city (city_name)
values
	('Москва'),
	('Астана'),
	('Волгоград'),
	('Buchen');

CREATE TABLE street (
  street_id int NOT NULL AUTO_INCREMENT,
  street_name varchar(100) NOT NULL,
  PRIMARY KEY (street_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO street (street_name) VALUES 
  ('Тверская'),
  ('Арбат'),
  ('Пресненская набережная'),
  ('Новый Арбат'),
  ('Садовое Кольцо');

INSERT INTO street (street_name) VALUES 
  ('Бейбитшилик'),
  ('Габдуллина'),
  ('Проспект Абая'),
  ('Проспект Сары-Арка'),
  ('Проспект Республики');

INSERT INTO street (street_name) VALUES 
  ('Ленинградская'),
  ('Ленина'),
  ('9 Января'),
  ('Мира'),
  ('Дзержинского');

INSERT INTO street (street_name) VALUES 
  ('Hauptstraße'),
  ('Schulstraße'),
  ('Kirchweg'),
  ('Am Markt'),
  ('Bahnhofstraße');

CREATE TABLE address (
  address_id int NOT NULL AUTO_INCREMENT,
  building_number varchar(10) DEFAULT NULL,
  city_id int DEFAULT NULL,
  street_id int DEFAULT NULL,
  PRIMARY KEY (address_id),
  FOREIGN KEY (city_id) REFERENCES city (city_id),
  FOREIGN KEY (street_id) REFERENCES street (street_id)
);

-- Москва
INSERT INTO address (building_number, city_id, street_id)
VALUES 
  ('1A', 1, 1),
  ('2B', 1, 2),
  ('3C', 1, 3),
  ('4D', 1, 4),
  ('5E', 1, 5);

-- Астана
INSERT INTO address (building_number, city_id, street_id)
VALUES 
  ('11', 2, 6),
  ('12', 2, 7),
  ('13b', 2, 8),
  ('14', 2, 9),
  ('15', 2, 10);

-- Волгоград
INSERT INTO address (building_number, city_id, street_id)
VALUES 
  ('1', 3, 11),
  ('2', 3, 12),
  ('3', 3, 13),
  ('3a', 3, 14),
  ('5', 3, 15);

-- Бухен
INSERT INTO address (building_number, city_id, street_id)
VALUES 
  ('1', 4, 16),
  ('2', 4, 17),
  ('3', 4, 18),
  ('4', 4, 19),
  ('5', 4, 20);

CREATE TABLE voucher (
  voucher_id int NOT NULL AUTO_INCREMENT,
  voucher_content varchar(30) NOT NULL,
  discount_percentage tinyint DEFAULT NULL,
  applies_left_count int DEFAULT NULL,
  PRIMARY KEY (voucher_id)
);

INSERT INTO voucher (voucher_content, discount_percentage, applies_left_count)
VALUES 
  ('CIRNO9', 9, 100),
  ('DISCOUNT25', 25, 50),
  ('SALE50OFF', 50, 20),
  ('HEHEHEHA', 5, 200),
  ('SPECIAL20', 20, 30);


CREATE TABLE customer (
  customer_id int NOT NULL AUTO_INCREMENT,
  customer_mail varchar(100) NOT NULL UNIQUE,
  customer_phone varchar(20) DEFAULT NULL UNIQUE,
  customer_last_name varchar(50) DEFAULT NULL,
  customer_first_name varchar(50) NOT NULL,
  customer_password varchar(30) NOT NULL,
  PRIMARY KEY (customer_id)
);

DELIMITER //

-- Процедура форматирования номера телефона
CREATE PROCEDURE fumo_plush_store.FormatPhoneNumber(
    IN phoneNumber VARCHAR(255),
    OUT formattedPhoneNumber VARCHAR(255)
)
BEGIN
    SET phoneNumber = REGEXP_REPLACE(phoneNumber, '[^0-9]', '');

   	IF LENGTH(phoneNumber) = 10 THEN
        SET phoneNumber = CONCAT('7', phoneNumber);
    END IF;
    IF LEFT(phoneNumber, 1) = '8' THEN
        SET phoneNumber = CONCAT('7', SUBSTRING(phoneNumber, 2));
    END IF;

    SET formattedPhoneNumber = CONCAT('+', phoneNumber);
END //

-- Триггер перед вставкой покупателя
CREATE TRIGGER BeforeInsertCustomer
BEFORE INSERT ON customer
FOR EACH ROW
BEGIN
	IF NEW.customer_mail NOT REGEXP '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,4}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format';
    END IF;
	
    CALL fumo_plush_store.FormatPhoneNumber(NEW.customer_phone, @formattedPhone);
    SET NEW.customer_phone = @formattedPhone;
END //

DELIMITER ;

INSERT INTO customer (customer_mail, customer_phone, customer_last_name, customer_first_name, customer_password)
VALUES 
  ('dyaika@example.ru', '+7(999)9999999', 'Razshildyaev', 'Aleksandr', 'mysecretpass'),
  ('sentry9@example.ru', '8(999)9999991', 'Shabalov', 'Aleksandr', 'strongpassword789'),
  ('doofUHD@example.de', '8(999)9999921', 'Archipov', 'Edward', 'strongpassword123'),
  ('m2a@example.de', '8(999)2999991', 'Archipov', 'Albert', 'securepass456'),
  ('warkrafter246@examole.com', '8(999)9979991', 'Hudyakov', 'Mark', 'password321');

CREATE TABLE item (
  item_id int NOT NULL AUTO_INCREMENT,
  item_name varchar(100) NOT NULL,
  item_cost double NOT NULL,
  item_description text DEFAULT NULL,
  item_release_year year DEFAULT NULL,
  item_width float DEFAULT NULL,
  item_height float DEFAULT NULL,
  image_url text DEFAULT NULL,
  PRIMARY KEY (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO item (item_name, item_cost, item_description, item_release_year, item_width, item_height, image_url)
VALUES 
  ('Рейму Хакурей', 2500.00, '"Тебе известна поговорка: Хорошее лекарство на вкус горько?"\nМягкая и приятная игрушка с изображением Рейму Хакурей из Touhou Project. Gift, официальные японские производители, в сотрудничестве с группой "ANGELTYPE" разработали дизайн этих мягких игрушек и в точности воспроизвели очарование Рейму. ', 2008, 14.0, 21.0, '/images/reimuV1.png'),
  ('Мариса Кирисаме', 2800.00, '"Ваа, так много книг... Надо будет прихватить себе парочку на обратном пути"\nМилая плюшевая Мариса Кирисаме, вдохновленный Touhou Project. Пускай эти прекрасные куклы Фумо будут дарить Вам только положительные эмоции, уют, комфорт и хорошее настроение! Собери их всех!', 2008, 15.0, 25.0, '/images/marisaV1.png'),
  ('Ююко Сайгёдзи', 3500.00, '"Я видела зловещий сон... Весенний ветер унес всю нашу весну. И ветер был черным."\nПрекрасная плюшевая игрушка модели Touhou Plushie Series [Yuyuko Saigyouji] с тонкими деталями. На этот раз мы ещё более тщательно воспроизвели детали костюма! Рост сидя составляет около 20 см, а шляпа является съёмной!', 2010, 15.0, 22.0, '/images/yuyukoV1.png'),
  ('Фландр Скарлетт', 4200.00, 'Очаровательная и красочная плюшевая игрушка Flandre Scarlett из Touhou Project.', 2009, 28.0, 20.0, '/images/flandreV1.png'),
  ('Ёму Конпаку', 3200.00, '"Еще немного весны, и Сайгё Аякаси зацветёт полностью"\nПлюшевая игрушка Touhou Plushie Series [Youmu Konpaku] с изображением Ёму с мечом, обязательная для поклонников Touhou.', 2010, 17.0, 21.0, '/images/youmuV1.png');

CREATE TABLE notification (
  notification_id int NOT NULL AUTO_INCREMENT,
  notification_title varchar(100) NOT NULL,
  notification_content text NOT NULL,
  customer_id int NOT NULL,
  PRIMARY KEY (notification_id),
  FOREIGN KEY (customer_id) REFERENCES customer (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO notification (notification_title, notification_content, customer_id)
VALUES 
  ('Скидка на товар', 'Уважаемый клиент, у нас есть специальное предложение: скидка 9% на все товары! Воспользуйтесь промокодом "CIRNO9".', 1),
  ('Новинка в магазине', 'Мы рады представить вам новый товар в нашем магазине - Flandre Scarlett Fumofumo. Поторопитесь, количество ограничено!', 2);

CREATE TABLE store (
  store_id int NOT NULL AUTO_INCREMENT,
  open_time time DEFAULT NULL,
  close_time time DEFAULT NULL,
  address_id int NOT NULL,
  PRIMARY KEY (store_id),
  FOREIGN KEY (address_id) REFERENCES address (address_id)
);

INSERT INTO store (open_time, close_time, address_id)
VALUES 
  ('09:00:00', '18:00:00', 1),
  ('10:30:00', '20:00:00', 10),
  ('08:00:00', '17:30:00', 15),
  ('11:00:00', '19:00:00', 16);

CREATE TABLE myorder (
  order_id int NOT NULL AUTO_INCREMENT,
  order_status_id tinyint NOT NULL,
  voucher_id int DEFAULT NULL,
  store_id int DEFAULT NULL,
  customer_id int NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (voucher_id) REFERENCES voucher (voucher_id),
  FOREIGN KEY (store_id) REFERENCES store (store_id),
  FOREIGN KEY (customer_id) REFERENCES customer (customer_id),
  FOREIGN KEY (order_status_id) REFERENCES order_status (status_id)
);

CREATE TABLE itemorder_association (
  order_id int NOT NULL,
  item_id int NOT NULL,
  item_count int NOT NULL,
  item_price double NOT NULL,
  PRIMARY KEY (order_id, item_id),
  FOREIGN KEY (item_id) REFERENCES item (item_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);

DELIMITER //

-- Триггер перед вставкой в myorder
CREATE TRIGGER BeforeInsertOrders
BEFORE INSERT ON myorder
FOR EACH ROW
BEGIN
	SET @promo = NEW.voucher_id;
	IF @promo IS NOT NULL THEN
		SELECT v2.applies_left_count
		INTO @count
		FROM voucher v2 
		WHERE v2.voucher_id = @promo;
		IF @count < 1 THEN
			SIGNAL SQLSTATE '45000'
        	SET MESSAGE_TEXT = 'This voucher is not available';
        ELSE
        	UPDATE voucher SET applies_left_count = @count - 1
        	WHERE voucher_id = @promo;
        END IF;
	END IF;
END //

-- Триггер после вставки в myorder
CREATE TRIGGER fumo_plush_store.AfterInsertOrders
AFTER INSERT ON myorder
FOR EACH ROW
BEGIN
	SELECT c.customer_first_name
	INTO @name
	FROM customer c
	WHERE c.customer_id = NEW.customer_id;

	SELECT os.status_name
	INTO @status
	FROM order_status os
	WHERE os.status_id = NEW.order_status_id;
	INSERT INTO notification (notification_title, notification_content, customer_id)
	VALUES
		(CONCAT('Новый статус заказа №', NEW.order_id), CONCAT(@name, ', Вашему заказу присвоен статус ', @status), NEW.customer_id);
END //

-- Триггер перед вставкой в itemorder_association
CREATE TRIGGER BeforeInsertItemOrderAssociation
BEFORE INSERT ON itemorder_association
FOR EACH ROW
BEGIN
	SELECT i.item_cost
	INTO @price
	FROM item i
	WHERE i.item_id = NEW.item_id;
	SET NEW.item_price = @price;
END //

DELIMITER ;


-- Заказ 1
INSERT INTO myorder (order_status_id, store_id, customer_id, voucher_id)
VALUES 
  (1, 1, 3, 2);
INSERT INTO itemorder_association (order_id, item_id, item_count)
VALUES 
  (1, 1, 2);

-- Заказ 2
INSERT INTO myorder (order_status_id, store_id, customer_id)
VALUES 
  (2, 2, 1);
INSERT INTO itemorder_association (order_id, item_id, item_count)
VALUES 
  (2, 2, 1),
  (2, 1, 1);
  
-- Заказ 3
INSERT INTO myorder (order_status_id, store_id, customer_id)
VALUES 
  (1, 3, 2);
INSERT INTO itemorder_association (order_id, item_id, item_count)
VALUES 
  (3, 3, 1),
  (3, 2, 1),
  (3, 1, 1);

-- Заказ 4
INSERT INTO myorder (order_status_id, store_id, customer_id)
VALUES
  (5, 4, 3);
INSERT INTO itemorder_association (order_id, item_id, item_count)
VALUES 
  (4, 4, 1);
  
DELIMITER //

-- Функция подсчета стоимости заказа (с учетом промокода)
CREATE FUNCTION fumo_plush_store.GetOrderTotalCost(orderId INT) RETURNS DOUBLE READS SQL DATA
BEGIN
    DECLARE totalCost DOUBLE;
    SELECT SUM(ia.item_price * ia.item_count)
    INTO totalCost
    FROM itemorder_association ia
   	WHERE ia.order_id = orderId;
    RETURN totalCost;
END //

DELIMITER ;

CREATE TABLE review (
  review_text text DEFAULT NULL,
  rating tinyint CHECK (rating >= 0 AND rating <= 5) NOT NULL,
  order_id int NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO review (review_text, rating, order_id)
VALUES 
  ('Отличная игрушка! Большое спасибо, всё как ожидалось. Рейтинг 5/5.', 5, 4);

CREATE TABLE checks (
  check_print_time datetime NOT NULL,
  order_id int NOT NULL,
  paid_in_cash double DEFAULT NULL,
  paid_by_card double DEFAULT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);

INSERT INTO checks (check_print_time, order_id, paid_in_cash, paid_by_card)
VALUES 
  ('2023-11-11 15:45:00', 2, 2500.00, NULL),
  ('2023-11-13 16:00:00', 4, NULL, 4200.00);

CREATE TABLE itemstore_association (
  	item_id int NOT NULL,
	store_id int NOT NULL,
	item_count int CHECK (item_count >= 0) NOT NULL,
  	PRIMARY KEY (item_id, store_id),
  	FOREIGN KEY (item_id) REFERENCES item (item_id),
  	FOREIGN KEY (store_id) REFERENCES store (store_id)
);

INSERT INTO itemstore_association (item_id, store_id, item_count)
VALUES
	(1, 1, 100),
	(2, 1, 90),
	(3, 1, 80),
	(4, 1, 70),
	(5, 1, 60),
	(1, 2, 55),
	(2, 2, 94),
	(3, 2, 40),
	(4, 2, 50),
	(5, 2, 65),
	(1, 3, 190),
	(2, 3, 20),
	(3, 3, 33),
	(4, 3, 7),
	(5, 3, 61),
	(1, 4, 110),
	(2, 4, 10),
	(3, 4, 64),
	(4, 4, 38),
	(5, 4, 67);

CREATE VIEW stock AS
SELECT
    i.item_id,
    i.item_name,
    i.item_cost,
    COALESCE(SUM(ia.item_count), 0) AS "item_count"
FROM
    item i
LEFT JOIN
    itemstore_association ia ON ia.item_id = i.item_id
LEFT JOIN
    store s ON s.store_id = ia.store_id
GROUP BY
    i.item_id, i.item_name, i.item_cost;
