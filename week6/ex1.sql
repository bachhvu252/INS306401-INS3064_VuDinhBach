SELECT
    p.id AS product_id,
    p.name AS product_name,
    p.price,
    c.name AS category_name
FROM products p
LEFT JOIN categories c 
    ON p.category_id = c.id
ORDER BY 
    p.id;