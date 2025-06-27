DELIMITER //

CREATE PROCEDURE create_stocks(
    IN p_product_category_id INT,
    IN p_ontvangdatum DATE,
    IN p_uigeleverddatum DATE,
    IN p_eenheid VARCHAR(50),
    IN p_aantalOpVoorad INT,
    IN p_aantalUigegeven INT,
    IN p_aantalBijgeleverd INT,
    IN p_is_active TINYINT(1),
    IN p_note TEXT
)
BEGIN
    INSERT INTO stocks (
        product_category_id,
        ontvangdatum,
        uigeleverddatum,
        eenheid,
        aantalOpVoorad,
        aantalUigegeven,
        aantalBijgeleverd,
        is_active,
        note,
        created_at,
        updated_at
    ) VALUES (
        p_product_category_id,
        p_ontvangdatum,
        p_uigeleverddatum,
        p_eenheid,
        p_aantalOpVoorad,
        p_aantalUigegeven,
        p_aantalBijgeleverd,
        p_is_active,
        p_note,
        NOW(),
        NOW()
    );
END //

DELIMITER ;
