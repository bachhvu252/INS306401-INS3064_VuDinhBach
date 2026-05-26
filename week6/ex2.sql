SELECT 
    p.id, 
    p.name
FROM products p
LEFT JOIN categories c 
    ON p.category_id = c.id
WHERE 
    c.id IS NULL
ORDER BY 
    p.id;
