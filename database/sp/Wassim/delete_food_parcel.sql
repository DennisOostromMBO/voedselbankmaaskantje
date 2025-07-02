CREATE PROCEDURE sp_delete_food_parcel(
    IN p_id INT
)
BEGIN
    DELETE FROM food_parcels 
    WHERE id = p_id;
END;
