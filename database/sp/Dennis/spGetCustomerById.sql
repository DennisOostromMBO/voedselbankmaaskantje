DROP PROCEDURE IF EXISTS spGetCustomerById;
CREATE PROCEDURE spGetCustomerById(IN customerId INT)
BEGIN
    SELECT
        customers.id,
        persons.full_name,
        families.name AS family_name,
        persons.age,
        contacts.full_address,
        contacts.mobile,
        contacts.email,
        customers.number AS customer_number,
        wishes.choices AS wish
    FROM customers
    INNER JOIN families ON customers.family_id = families.id
    INNER JOIN persons ON families.person_id = persons.id
    INNER JOIN contacts ON contacts.customer_id = customers.id
    LEFT JOIN wishes ON customers.id = wishes.customer_id
    WHERE customers.id = customerId;
END;
