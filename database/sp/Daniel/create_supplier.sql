CREATE PROCEDURE create_supplier(
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
    IN p_mobile VARCHAR(10),
    IN p_upcoming_delivery_at DATETIME
)
BEGIN
    INSERT INTO suppliers (supplier_name, contact_number, is_active, note, upcoming_delivery_at, created_at, updated_at)
    VALUES (p_supplier_name, p_contact_number, p_is_active, p_note, p_upcoming_delivery_at, NOW(), NOW());

    -- Insert contact info if at least one contact field is present
    IF p_email IS NOT NULL OR p_street IS NOT NULL OR p_house_number IS NOT NULL OR p_postcode IS NOT NULL OR p_city IS NOT NULL OR p_mobile IS NOT NULL THEN
        INSERT INTO contacts (
            supplier_id,
            street,
            house_number,
            addition,
            postcode,
            city,
            mobile,
            email,
            is_active,
            created_at,
            updated_at
        ) VALUES (
            LAST_INSERT_ID(),
            p_street,
            p_house_number,
            p_addition,
            p_postcode,
            p_city,
            p_mobile,
            p_email,
            1,
            NOW(),
            NOW()
        );
    END IF;
END;
