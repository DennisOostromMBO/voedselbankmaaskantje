DELIMITER //

CREATE PROCEDURE create_stocks(
    IN p_product_category_id INT,
    IN p_is_active TINYINT(1),
    IN p_note TEXT,
    IN p_received_date DATE,
    IN p_delivered_date DATE,
    IN p_unit VARCHAR(50),
    IN p_quantity_in_stock INT,
    IN p_quantity_delivered INT,
    IN p_quantity_supplied INT
)
BEGIN
    INSERT INTO stocks (
        product_category_id,
        is_active,
        note,
        received_date,
        delivered_date,
        unit,
        quantity_in_stock,
        quantity_delivered,
        quantity_supplied,
        created_at,
        updated_at
    ) VALUES (
        p_product_category_id,
        p_is_active,
        p_note,
        p_received_date,
        p_delivered_date,
        p_unit,
        p_quantity_in_stock,
        p_quantity_delivered,
        p_quantity_supplied,
        NOW(),
        NOW()
    );
END //

DELIMITER ;
