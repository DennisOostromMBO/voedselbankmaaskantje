DELIMITER //

CREATE PROCEDURE get_all_stocks()
BEGIN
    SELECT
        s.id,
        s.product_category_id,
        s.is_active,
        s.note,
        s.created_at,
        s.updated_at,
        s.ontvangdatum,
        s.uigeleverddatum,
        s.eenheid,
        s.aantalOpVoorad,
        s.aantalUigegeven,
        s.aantalBijgeleverd,
        pc.category_name,
        pc.is_active AS category_is_active,
        pc.note AS category_note,
        p.product_name,
        p.number
    FROM stocks s
    LEFT JOIN product_categories pc ON pc.id = s.product_category_id
    LEFT JOIN products p ON p.id = pc.product_id;
END //

DELIMITER ;