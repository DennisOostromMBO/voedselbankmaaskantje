CREATE PROCEDURE sp_update_food_parcel(
    IN p_id INT,
    IN p_stock_id INT,
    IN p_customer_id INT,
    IN p_is_active BOOLEAN,
    IN p_note TEXT
)
BEGIN
    UPDATE food_parcels
    SET
        stock_id = p_stock_id,
        customer_id = p_customer_id,
        is_active = COALESCE(p_is_active, TRUE),
        note = p_note,
        updated_at = NOW()
    WHERE id = p_id;
END;
