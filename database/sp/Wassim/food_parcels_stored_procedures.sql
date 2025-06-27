-- =====================================================
-- Food Parcels Stored Procedures
-- Author: Wassim
-- Description: Comprehensive stored procedures for food parcel management
-- with complex joins and business logic
-- =====================================================

USE voedselbankmaaskantje;

DELIMITER //

-- =====================================================
-- SP: Get all food parcels with customer and stock details
-- Includes filtering by customer, active status, and search
-- =====================================================
DROP PROCEDURE IF EXISTS sp_get_food_parcels_with_details //

CREATE PROCEDURE sp_get_food_parcels_with_details(
    IN p_customer_id INT,
    IN p_is_active BOOLEAN,
    IN p_search_term VARCHAR(255)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT
        fp.id,
        fp.stock_id,
        fp.customer_id,
        fp.is_active,
        fp.note,
        fp.created_at,
        fp.updated_at,
        -- Customer details (through family and person)
        c.number AS customer_number,
        f.name AS family_name,
        CONCAT(p.first_name, ' ', IFNULL(p.infix, ''), ' ', p.last_name) AS customer_name,
        p.first_name AS customer_first_name,
        p.last_name AS customer_last_name,
        -- Stock details
        s.is_active AS stock_is_active,
        -- Product category details
        pc.name AS category_name,
        pc.description AS category_description,
        -- Additional computed fields
        CASE
            WHEN fp.is_active = 1 THEN 'Active'
            ELSE 'Inactive'
        END AS status_text
    FROM food_parcels fp
    INNER JOIN customers c ON fp.customer_id = c.id
    INNER JOIN families f ON c.family_id = f.id
    INNER JOIN persons p ON f.person_id = p.id
    INNER JOIN stocks s ON fp.stock_id = s.id
    INNER JOIN product_categories pc ON s.product_category_id = pc.id
    WHERE
        (p_customer_id IS NULL OR fp.customer_id = p_customer_id)
        AND (p_is_active IS NULL OR fp.is_active = p_is_active)
        AND (
            p_search_term IS NULL
            OR p_search_term = ''
            OR CONCAT(p.first_name, ' ', IFNULL(p.infix, ''), ' ', p.last_name) LIKE CONCAT('%', p_search_term, '%')
            OR c.number LIKE CONCAT('%', p_search_term, '%')
            OR f.name LIKE CONCAT('%', p_search_term, '%')
            OR pc.name LIKE CONCAT('%', p_search_term, '%')
            OR fp.note LIKE CONCAT('%', p_search_term, '%')
        )
    ORDER BY fp.created_at DESC;
END //

-- =====================================================
-- SP: Get food parcel details by ID
-- Returns comprehensive details for a single food parcel
-- =====================================================
DROP PROCEDURE IF EXISTS sp_get_food_parcel_by_id //

CREATE PROCEDURE sp_get_food_parcel_by_id(
    IN p_food_parcel_id INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT
        fp.id,
        fp.stock_id,
        fp.customer_id,
        fp.is_active,
        fp.note,
        fp.created_at,
        fp.updated_at,
        -- Customer details (through family and person)
        c.number AS customer_number,
        f.name AS family_name,
        p.first_name AS customer_first_name,
        p.last_name AS customer_last_name,
        p.infix AS customer_infix,
        CONCAT(p.first_name, ' ', IFNULL(p.infix, ''), ' ', p.last_name) AS customer_name,
        p.age AS customer_age,
        -- Stock details
        s.is_active AS stock_is_active,
        s.note AS stock_note,
        -- Product category details
        pc.id AS category_id,
        pc.name AS category_name,
        pc.description AS category_description
    FROM food_parcels fp
    INNER JOIN customers c ON fp.customer_id = c.id
    INNER JOIN families f ON c.family_id = f.id
    INNER JOIN persons p ON f.person_id = p.id
    INNER JOIN stocks s ON fp.stock_id = s.id
    INNER JOIN product_categories pc ON s.product_category_id = pc.id
    WHERE fp.id = p_food_parcel_id;
END //

-- =====================================================
-- SP: Create new food parcel
-- Includes validation and automatic status handling
-- =====================================================
DROP PROCEDURE IF EXISTS sp_create_food_parcel //

CREATE PROCEDURE sp_create_food_parcel(
    IN p_stock_id INT,
    IN p_customer_id INT,
    IN p_is_active BOOLEAN,
    IN p_note TEXT
)
BEGIN
    DECLARE v_stock_exists INT DEFAULT 0;
    DECLARE v_customer_exists INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- Validate stock exists
    SELECT COUNT(*)
    INTO v_stock_exists
    FROM stocks
    WHERE id = p_stock_id AND is_active = 1;

    IF v_stock_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock not found or inactive';
    END IF;

    -- Validate customer exists
    SELECT COUNT(*) INTO v_customer_exists
    FROM customers
    WHERE id = p_customer_id AND is_active = 1;

    IF v_customer_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Customer not found or inactive';
    END IF;

    -- Insert food parcel
    INSERT INTO food_parcels (
        stock_id,
        customer_id,
        is_active,
        note,
        created_at,
        updated_at
    ) VALUES (
        p_stock_id,
        p_customer_id,
        COALESCE(p_is_active, TRUE),
        p_note,
        NOW(),
        NOW()
    );

    COMMIT;
END //

-- =====================================================
-- SP: Update food parcel
-- Includes validation and quantity adjustments
-- =====================================================
DROP PROCEDURE IF EXISTS sp_update_food_parcel //

CREATE PROCEDURE sp_update_food_parcel(
    IN p_food_parcel_id INT,
    IN p_stock_id INT,
    IN p_customer_id INT,
    IN p_is_active BOOLEAN,
    IN p_note TEXT
)
BEGIN
    DECLARE v_stock_exists INT DEFAULT 0;
    DECLARE v_customer_exists INT DEFAULT 0;
    DECLARE v_food_parcel_exists INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- Check if food parcel exists
    SELECT COUNT(*)
    INTO v_food_parcel_exists
    FROM food_parcels
    WHERE id = p_food_parcel_id;

    IF v_food_parcel_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Food parcel not found';
    END IF;

    -- Validate new stock exists and is active
    SELECT COUNT(*)
    INTO v_stock_exists
    FROM stocks
    WHERE id = p_stock_id AND is_active = 1;

    IF v_stock_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock not found or inactive';
    END IF;

    -- Validate customer exists and is active
    SELECT COUNT(*) INTO v_customer_exists
    FROM customers
    WHERE id = p_customer_id AND is_active = 1;

    IF v_customer_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Customer not found or inactive';
    END IF;

    -- Update food parcel
    UPDATE food_parcels
    SET
        stock_id = p_stock_id,
        customer_id = p_customer_id,
        is_active = COALESCE(p_is_active, TRUE),
        note = p_note,
        updated_at = NOW()
    WHERE id = p_food_parcel_id;

    COMMIT;
END //

-- =====================================================
-- SP: Delete food parcel
-- Includes stock quantity restoration
-- =====================================================
DROP PROCEDURE IF EXISTS sp_delete_food_parcel //

CREATE PROCEDURE sp_delete_food_parcel(
    IN p_food_parcel_id INT
)
BEGIN
    DECLARE v_food_parcel_exists INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- Check if food parcel exists
    SELECT COUNT(*)
    INTO v_food_parcel_exists
    FROM food_parcels
    WHERE id = p_food_parcel_id;

    IF v_food_parcel_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Food parcel not found';
    END IF;

    -- Delete food parcel
    DELETE FROM food_parcels
    WHERE id = p_food_parcel_id;

    COMMIT;
END //

-- =====================================================
-- SP: Get food parcel statistics
-- Returns comprehensive stats for dashboard
-- =====================================================
DROP PROCEDURE IF EXISTS sp_get_food_parcel_stats //

CREATE PROCEDURE sp_get_food_parcel_stats()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
        SUM(CASE
            WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE())-1 DAY)
            THEN 1
            ELSE 0
        END) as this_month,
        SUM(CASE
            WHEN DATE(created_at) = CURDATE()
            THEN 1
            ELSE 0
        END) as today,
        SUM(CASE
            WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            THEN 1
            ELSE 0
        END) as this_week
    FROM food_parcels;
END //

DELIMITER ;

-- =====================================================
-- Grant permissions (adjust as needed for your setup)
-- =====================================================
-- GRANT EXECUTE ON PROCEDURE sp_get_food_parcels_with_details TO 'webapp_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE sp_get_food_parcel_by_id TO 'webapp_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE sp_create_food_parcel TO 'webapp_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE sp_update_food_parcel TO 'webapp_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE sp_delete_food_parcel TO 'webapp_user'@'localhost';
-- GRANT EXECUTE ON PROCEDURE sp_get_food_parcel_stats TO 'webapp_user'@'localhost';
