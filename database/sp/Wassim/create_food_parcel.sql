DELIMITER //

DROP PROCEDURE IF EXISTS sp_create_food_parcel //

CREATE PROCEDURE sp_create_food_parcel(
    IN p_stock_id INT,
    IN p_customer_id INT,
    IN p_is_active BOOLEAN,
    IN p_note TEXT
)
BEGIN
    INSERT INTO food_parcels (
        stock_id,
        customer_id,
        is_active,
        note,
        created_at,
        updated_at
    ) VALUES (
        p_stock_id,
        p_customer_id,
        p_is_active,
        p_note,
        NOW(),
        NOW()
    );
END //

DELIMITER ;
