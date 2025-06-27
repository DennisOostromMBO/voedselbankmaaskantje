DELIMITER //

CREATE PROCEDURE destroy_stocks(
    IN p_id INT
)
BEGIN
    DELETE FROM stocks WHERE id = p_id;
END //

DELIMITER ;
