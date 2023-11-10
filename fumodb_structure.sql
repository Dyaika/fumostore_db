CREATE TABLE city (
  city_id int NOT NULL AUTO_INCREMENT,
  city_name varchar(25) NOT NULL,
  PRIMARY KEY (city_id)
);

CREATE TABLE street (
  street_id int NOT NULL AUTO_INCREMENT,
  street_name varchar(100) NOT NULL,
  PRIMARY KEY (street_id)
);

CREATE TABLE address (
  address_id int NOT NULL AUTO_INCREMENT,
  building_number varchar(10) DEFAULT NULL,
  city_id int DEFAULT NULL,
  street_id int DEFAULT NULL,
  PRIMARY KEY (address_id),
  FOREIGN KEY (city_id) REFERENCES city (city_id),
  FOREIGN KEY (street_id) REFERENCES street (street_id)
);

CREATE TABLE voucher (
  voucher_id int NOT NULL AUTO_INCREMENT,
  voucher_content varchar(30) NOT NULL,
  discount_percentage tinyint DEFAULT NULL,
  applies_left_count int DEFAULT NULL,
  PRIMARY KEY (voucher_id)
);

CREATE TABLE customer (
  customer_id int NOT NULL AUTO_INCREMENT,
  customer_mail varchar(100) NOT NULL,
  customer_phone varchar(20) DEFAULT NULL,
  customer_last_name varchar(50) DEFAULT NULL,
  customer_first_name varchar(50) NOT NULL,
  customer_password varchar(30) NOT NULL,
  PRIMARY KEY (customer_id)
);

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

CREATE TABLE notification (
  notification_id int NOT NULL AUTO_INCREMENT,
  notification_title varchar(100) NOT NULL,
  notification_content text NOT NULL,
  customer_id int NOT NULL,
  PRIMARY KEY (notification_id),
  FOREIGN KEY (customer_id) REFERENCES customer (customer_id)
);

CREATE TABLE store (
  store_id int NOT NULL AUTO_INCREMENT,
  open_time time DEFAULT NULL,
  close_time time DEFAULT NULL,
  address_id int NOT NULL,
  PRIMARY KEY (store_id),
  FOREIGN KEY (address_id) REFERENCES address (address_id)
);

CREATE TABLE myorder (
  order_id int NOT NULL AUTO_INCREMENT,
  order_status tinyint NOT NULL,
  order_cost double NOT NULL,
  voucher_id int DEFAULT NULL,
  store_id int DEFAULT NULL,
  customer_id int NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (voucher_id) REFERENCES voucher (voucher_id),
  FOREIGN KEY (store_id) REFERENCES store (store_id),
  FOREIGN KEY (customer_id) REFERENCES customer (customer_id)
);

CREATE TABLE review (
  review_text text DEFAULT NULL,
  rating tinyint NOT NULL,
  order_id int NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);

CREATE TABLE itemorder_association (
  item_count int NOT NULL,
  item_id int NOT NULL,
  order_id int NOT NULL,
  PRIMARY KEY (order_id, item_id),
  FOREIGN KEY (item_id) REFERENCES item (item_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);

CREATE TABLE checks (
  check_print_time datetime NOT NULL,
  order_id int NOT NULL,
  paid_in_cash double DEFAULT NULL,
  paid_by_card double DEFAULT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (order_id) REFERENCES myorder (order_id)
);
