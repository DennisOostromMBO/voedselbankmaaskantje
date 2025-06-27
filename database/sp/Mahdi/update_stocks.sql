DELIMITER //

CREATE PROCEDURE update_stocks(
    IN p_id INT,
    IN p_quantity_in_stock INT,
    IN p_quantity_delivered INT,
    IN p_quantity_supplied INT
)
BEGIN
    UPDATE stocks
    SET
        quantity_in_stock = p_quantity_in_stock,
        quantity_delivered = p_quantity_delivered,
        quantity_supplied = p_quantity_supplied,
        updated_at = NOW()
    WHERE id = p_id;
END //

DELIMITER ;
