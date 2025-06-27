CREATE PROCEDURE update_supplier(
    IN p_id BIGINT,
    IN p_supplier_name VARCHAR(255),
    IN p_contact_number VARCHAR(255),
    IN p_is_active BOOLEAN,
    IN p_note TEXT,
    IN p_email VARCHAR(255),
    IN p_street VARCHAR(100),
    IN p_house_number VARCHAR(4),
    IN p_addition VARCHAR(5),
    IN p_postcode VARCHAR(6),
    IN p_city VARCHAR(50),
    IN p_mobile VARCHAR(10)
)
BEGIN
    UPDATE suppliers
    SET
        supplier_name = p_supplier_name,
        contact_number = p_contact_number,
        is_active = p_is_active,
        note = p_note,
        updated_at = NOW()
    WHERE id = p_id;

    UPDATE contacts
    SET
        street = p_street,
        house_number = p_house_number,
        addition = p_addition,
        postcode = p_postcode,
        city = p_city,
        mobile = p_mobile,
        email = p_email,
        updated_at = NOW()
    WHERE supplier_id = p_id;
END;
