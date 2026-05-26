SELECT
    o.id AS order_id,
    u.email AS customer_email,
    p.name AS product_name,
    oi.quantity,
    oi.price
FROM orders o
JOIN users u 
    ON o.user_id = u.id
JOIN order_items oi 
    ON o.id = oi.order_id
JOIN products p 
    ON oi.product_id = p.id
WHERE 
    o.status = 'delivered'
ORDER BY 
    o.id, 
    p.id;