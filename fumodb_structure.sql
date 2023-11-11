CREATE TABLE city (
  city_id int NOT NULL AUTO_INCREMENT,
  city_name varchar(25) NOT NULL,
  PRIMARY KEY (city_id)
);

INSERT INTO city (city_name)
values
	('Moscow'),
	('Astana'),
	('Volgograd'),
	('Buchen');

CREATE TABLE street (
  street_id int NOT NULL AUTO_INCREMENT,
  street_name varchar(100) NOT NULL,
  PRIMARY KEY (street_id)
);

INSERT INTO street (street_name) VALUES 
  ('Tverskaya Street'),
  ('Arbat'),
  ('Presnenskaya Embankment'),
  ('Novy Arbat'),
  ('Sadovoye Koltso');

INSERT INTO street (street_name) VALUES 
  ('Presnenskaya Street'),
  ('Abay'),
  ('Dostyk'),
  ('Kabanbay Batyr'),
  ('Kenesary');

INSERT INTO street (street_name) VALUES 
  ('Leningradskaya Street'),
  ('Lenina Avenue'),
  ('9 Yanvarya'),
  ('Mira'),
  ('Dzerzhinskogo');

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
  customer_mail varchar(100) NOT NULL,
  customer_phone varchar(20) DEFAULT NULL,
  customer_last_name varchar(50) DEFAULT NULL,
  customer_first_name varchar(50) NOT NULL,
  customer_password varchar(30) NOT NULL,
  PRIMARY KEY (customer_id)
);

INSERT INTO customer (customer_mail, customer_phone, customer_last_name, customer_first_name, customer_password)
VALUES 
  ('dyaika@example.ru', '+1122334455', 'Razshildyaev', 'Aleksandr', 'mysecretpass'),
  ('sentry9@example.ru', '+9988776655', 'Shabalov', 'Aleksandr', 'strongpassword789'),
  ('doofUHD@example.de', '+1234567890', 'Archipov', 'Edward', 'strongpassword123'),
  ('m2a@example.de', '+9876543210', 'Archipov', 'Albert', 'securepass456'),
  ('warkrafter246@examole.com', '+1122334455', 'Hudyakov', 'Mark', 'password321');

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
);

INSERT INTO item (item_name, item_cost, item_description, item_release_year, item_width, item_height, image_url)
VALUES 
  ('Reimu Hakurei Fumofumo', 2500.00, 'Мягкая и приятная игрушка с изображением Рейму Хакурей из Touhou Project.', NULL, 30.0, 20.0, 'reimu_fumofumo.jpg'),
  ('Marisa Kirisame Fumofumo', 2800.00, 'Милый и пушистый плюшевый мариса Кирисаме, вдохновленный Touhou Project.', NULL, 25.0, 18.0, 'marisa_fumofumo.jpg'),
  ('Yuyuko Saigyoji Fumofumo', 3500.00, 'Прекрасный плюшевый Yuyuko Saigyoji с тонкими деталями.', NULL, 35.0, 22.0, 'yuyuko_fumofumo.jpg'),
  ('Flandre Scarlett Fumofumo', 4200.00, 'Очаровательная и красочная плюшевая игрушка Flandre Scarlett из Touhou Project.', NULL, 28.0, 25.0, 'flandre_fumofumo.jpg'),
  ('Youmu Konpaku Fumofumo', 3200.00, 'Плюшевая игрушка Fumofumo с изображением Youmu Konpaku с мечом, обязательная для поклонников Touhou.', NULL, 32.0, 15.0, 'youmu_fumofumo.jpg');

CREATE TABLE notification (
  notification_id int NOT NULL AUTO_INCREMENT,
  notification_title varchar(100) NOT NULL,
  notification_content text NOT NULL,
  customer_id int NOT NULL,
  PRIMARY KEY (notification_id),
  FOREIGN KEY (customer_id) REFERENCES customer (customer_id)
);

INSERT INTO notification (notification_title, notification_content, customer_id)
VALUES 
  ('Скидка на товар', 'Уважаемый клиент, у нас есть специальное предложение: скидка 9% на все товары! Воспользуйтесь промокодом "CIRNO9".', 1),
  ('Новинка в магазине', 'Здравствуйте! Мы рады представить вам новый товар в нашем магазине - Flandre Scarlett Fumofumo. Поторопитесь, количество ограничено!', 2),
  ('Подтверждение заказа', 'Спасибо за ваш заказ! Ваш заказ №1 успешно оформлен. Следите за статусом доставки в разделе "Мои заказы".', 3);

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
  ('10:30:00', '20:00:00', 6),
  ('08:00:00', '17:30:00', 11),
  ('11:00:00', '19:00:00', 16);

CREATE TABLE myorder (
  order_id int NOT NULL AUTO_INCREMENT,
  order_status tinyint NOT NULL, -- 1 создан 2 оплачен 3 получен 4 отменен
  order_cost double NOT NULL,
  voucher_id int DEFAULT NULL,
  store_id int DEFAULT NULL,
  customer_id int NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (voucher_id) REFERENCES voucher (voucher_id),
  FOREIGN KEY (store_id) REFERENCES store (store_id),
  FOREIGN KEY (customer_id) REFERENCES customer (customer_id)
);

INSERT INTO myorder (order_status, order_cost, store_id, customer_id)
VALUES 
  (1, 2500.00, 1, 3),
  (2, 2800.00, 2, 1),
  (1, 3500.00, 3, 2),
  (3, 4200.00, 4, 3);

CREATE TABLE review (
  review_text text DEFAULT NULL,
  rating tinyint CHECK (rating >= 0 AND rating <= 5) NOT NULL,
  order_id int NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);

INSERT INTO review (review_text, rating, order_id)
VALUES 
  ('Отличная игрушка! Большое спасибо, всё как ожидалось. Рейтинг 5/5.', 5, 3);


CREATE TABLE itemorder_association (
  item_count int NOT NULL,
  item_id int NOT NULL,
  order_id int NOT NULL,
  PRIMARY KEY (order_id, item_id),
  FOREIGN KEY (item_id) REFERENCES item (item_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);

INSERT INTO itemorder_association (item_count, item_id, order_id)
VALUES 
  (1, 1, 1),
  (1, 2, 2),
  (1, 3, 3),
  (1, 4, 4);

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