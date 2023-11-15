-- Выборка
SELECT *
FROM myorder m 
where m.order_cost < 3000;

-- Проекция
SELECT c.customer_last_name , c.customer_phone 
FROM customer c;

-- Объединение
select ia.item_id, 'itemorder_association' as types
FROM itemorder_association ia
UNION
SELECT c.customer_id, 'customer' as types
FROM customer c ;

-- Пересечение
select ia.item_id
FROM itemorder_association ia
INTERSECT
SELECT c.customer_id
FROM customer c ;

-- Разность
SELECT c.customer_id
FROM customer c 
EXCEPT
select ia.item_id
FROM itemorder_association ia;

-- Соединение
SELECT m.order_id, i.item_name, i.item_cost, ia.item_count
FROM myorder m
INNER JOIN itemorder_association ia ON ia.order_id = m.order_id
INNER JOIN item i ON i.item_id = ia.item_id
ORDER BY m.order_id;

-- Декартово произведение
select c.city_name, s.street_name 
FROM city c
CROSS JOIN street s;

-- Группировка
SELECT m.customer_id, COUNT(*) as orders_count
FROM myorder m 
GROUP BY m.customer_id;

-- Деление
WITH 
	dividend as (
	SELECT city_name, street_name 
	FROM city c
	CROSS JOIN street s
	WHERE c.city_name != 'Astana'
	UNION
	SELECT 'New York' as city_name, 'Mira' as street_name),
	
	divider as (
	SELECT street_name as street_name
	FROM street),
	
	helpy as (
	SELECT city_name
	FROM dividend
	GROUP BY city_name),
	
	tt as (
	SELECT *
	FROM helpy
	CROSS JOIN divider
	EXCEPT
	SELECT *
	FROM dividend
	)

SELECT *
FROM helpy
EXCEPT
SELECT tt.city_name
FROM tt;