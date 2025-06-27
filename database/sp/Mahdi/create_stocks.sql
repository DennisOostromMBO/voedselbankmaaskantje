DELIMITER //

CREATE PROCEDURE create_stocks(
    IN p_product_category_id INT,
    IN p_is_active TINYINT(1),
    IN p_note TEXT,
    IN p_ontvangdatum DATE,
    IN p_uigeleverddatum DATE,
    IN p_eenheid VARCHAR(50),
    IN p_aantalOpVoorad INT,
    IN p_aantalUigegeven INT,
    IN p_aantalBijgeleverd INT
)
BEGIN
    INSERT INTO stocks (
        product_category_id,
        is_active,
        note,
        ontvangdatum,
        uigeleverddatum,
        eenheid,
        aantalOpVoorad,
        aantalUigegeven,
        aantalBijgeleverd,
        created_at,
        updated_at
    ) VALUES (
        p_product_category_id,
        p_is_active,
        p_note,
        p_ontvangdatum,
        p_uigeleverddatum,
        p_eenheid,
        p_aantalOpVoorad,
        p_aantalUigegeven,
        p_aantalBijgeleverd,
        NOW(),
        NOW()
    );
END //

DELIMITER ;
