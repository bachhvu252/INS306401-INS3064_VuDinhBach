SELECT
    c.name AS category_name,
    COUNT(p.id) AS product_count
FROM categories c
LEFT JOIN products p 
    ON c.id = p.category_id
GROUP BY 
    c.id
ORDER BY 
    product_count DESC;