-- Агрегатная
SELECT 
	i.item_id,
	i.item_name,
	i.item_cost,
	i.item_cost - AVG(item_cost) OVER () as delta
FROM item i;

-- Ранжирующая
SELECT
	ia.order_id,
	ia.item_id,
	ROW_NUMBER() over (PARTITION BY order_id ORDER BY item_id) as position
FROM itemorder_association ia;

-- Смещения
SELECT
	ia.order_id,
	ia.item_id,
	LAG(item_id) over (PARTITION BY order_id ORDER BY item_id) as prev
FROM itemorder_association ia;

-- Свои
SELECT
	ia.order_id, 
	ia.item_id,
	ia.item_price,
	ia.item_price - AVG(item_price) 
	over (PARTITION BY order_id ORDER BY item_price) as "local delta"
FROM itemorder_association ia;

SELECT 
	i.item_id,
	i.item_name,
	i.item_cost,
	NTILE(2) OVER (ORDER BY item_cost) AS "ценовая группа"
FROM item i;