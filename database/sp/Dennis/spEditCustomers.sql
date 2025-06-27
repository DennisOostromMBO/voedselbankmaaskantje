DROP PROCEDURE IF EXISTS spEditCustomer;
DELIMITER //
CREATE PROCEDURE spEditCustomer(
    IN p_id INT,
    IN p_first_name VARCHAR(255),
    IN p_infix VARCHAR(50),
    IN p_last_name VARCHAR(255),
    IN p_family_name VARCHAR(255),
    IN p_full_address VARCHAR(255),
    IN p_mobile VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_age INT,
    IN p_wish VARCHAR(255)
)
BEGIN
    DECLARE v_person_id INT;
    DECLARE v_family_id INT;

    -- Haal family_id en person_id op
    SELECT family_id INTO v_family_id FROM customers WHERE id = p_id LIMIT 1;
    SELECT person_id INTO v_person_id FROM families WHERE id = v_family_id LIMIT 1;

    -- Update persons
    UPDATE persons
    SET persons.first_name = p_first_name,
        persons.infix = p_infix,
        persons.last_name = p_last_name,
        persons.age = p_age
    WHERE persons.id = v_person_id;

    -- Update families
    UPDATE families
    SET families.name = p_family_name
    WHERE families.id = v_family_id;

    -- Update contacts
    UPDATE contacts
    SET full_address = p_full_address,
        mobile = p_mobile,
        email = p_email
    WHERE customer_id = p_id;

    -- Update wishes
    UPDATE wishes
    SET choices = p_wish
    WHERE customer_id = p_id;
END //
DELIMITER ;
