DROP PROCEDURE IF EXISTS spCreateCustomer;
CREATE PROCEDURE spCreateCustomer(
    IN p_first_name VARCHAR(255),
    IN p_infix VARCHAR(50),
    IN p_last_name VARCHAR(255),
    IN p_family_name VARCHAR(255),
    IN p_street VARCHAR(255),
    IN p_house_number VARCHAR(10),
    IN p_addition VARCHAR(10),
    IN p_postcode VARCHAR(10),
    IN p_city VARCHAR(50),
    IN p_mobile VARCHAR(20),
    IN p_email VARCHAR(255),
    IN p_number VARCHAR(10),
    IN p_age INT,
    IN p_wish VARCHAR(255)
)
BEGIN
    DECLARE v_person_id INT;
    DECLARE v_family_id INT;
    DECLARE v_customer_id INT;

    -- Voeg persoon toe
    INSERT INTO persons (first_name, infix, last_name, is_active, age, created_at, updated_at)
    VALUES (p_first_name, p_infix, p_last_name, 1, p_age, NOW(), NOW());
    SET v_person_id = LAST_INSERT_ID();

    -- Voeg familie toe
    INSERT INTO families (person_id, family_member_id, name, is_active, created_at, updated_at)
    VALUES (v_person_id, NULL, p_family_name, 1, NOW(), NOW());
    SET v_family_id = LAST_INSERT_ID();

    -- Voeg klant toe
    INSERT INTO customers (family_id, number, is_active, created_at, updated_at)
    VALUES (v_family_id, p_number, 1, NOW(), NOW());
    SET v_customer_id = LAST_INSERT_ID();

    -- Voeg contact toe
    INSERT INTO contacts (family_id, customer_id, street, house_number, addition, postcode, city, mobile, email, is_active, created_at, updated_at)
    VALUES (v_family_id, v_customer_id, p_street, p_house_number, p_addition, p_postcode, p_city, p_mobile, p_email, 1, NOW(), NOW());

    -- Voeg wens toe
    INSERT INTO wishes (customer_id, choices, is_active, created_at, updated_at)
    VALUES (v_customer_id, p_wish, 1, NOW(), NOW());
END;
