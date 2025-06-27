CREATE PROCEDURE delete_supplier(IN p_id BIGINT, OUT p_result VARCHAR(255))
BEGIN
    DECLARE delivery_date DATETIME;

    SELECT upcoming_delivery_at INTO delivery_date
    FROM suppliers
    WHERE id = p_id;

    IF delivery_date IS NULL OR delivery_date < NOW() THEN
        DELETE FROM contacts WHERE supplier_id = p_id;
        DELETE FROM suppliers WHERE id = p_id;
        SET p_result = 'success';
    ELSE
        SET p_result = 'active_delivery';
    END IF;
END;
