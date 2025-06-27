DROP PROCEDURE IF EXISTS spEditCustomer;

CREATE PROCEDURE spEditCustomer(
    IN p_id INT,
    IN p_first_name VARCHAR(255),
    IN p_infix VARCHAR(50),
    IN p_last_name VARCHAR(255),
    IN p_street VARCHAR(255),
    IN p_house_number VARCHAR(10),
    IN p_addition VARCHAR(10),
    IN p_postcode VARCHAR(10),
    IN p_city VARCHAR(50),
    IN p_mobile VARCHAR(20),
    IN p_email VARCHAR(255),
    IN p_age INT,
    IN p_wish VARCHAR(255)
)
BEGIN
    DECLARE v_person_id INT;
    DECLARE v_family_id INT;

    -- Haal family_id en person_id op
    SELECT customers.family_id INTO v_family_id FROM customers WHERE customers.id = p_id LIMIT 1;
    SELECT families.person_id INTO v_person_id FROM families WHERE families.id = v_family_id LIMIT 1;

    -- Update persons
    UPDATE persons
    SET persons.first_name = p_first_name,
        persons.infix = p_infix,
        persons.last_name = p_last_name,
        persons.age = p_age
    WHERE persons.id = v_person_id;

    -- Update contacts
    UPDATE contacts
    SET street = p_street,
        house_number = p_house_number,
        addition = p_addition,
        postcode = p_postcode,
        city = p_city,
        mobile = p_mobile,
        email = p_email
    WHERE contacts.customer_id = p_id;

    -- Update wishes
    UPDATE wishes
    SET choices = p_wish
    WHERE wishes.customer_id = p_id;
END;


