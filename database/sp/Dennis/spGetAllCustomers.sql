DROP PROCEDURE IF EXISTS spGetAllCustomers;
CREATE PROCEDURE spGetAllCustomers()
BEGIN
    SELECT
        persons.full_name,
        families.name AS family_name,
        contacts.full_address,
        contacts.mobile,
        contacts.email,
        persons.age,
        customers.number AS customer_number,
        wishes.choices AS wish
    FROM contacts
    INNER JOIN customers ON contacts.customer_id = customers.id
    INNER JOIN families ON customers.family_id = families.id
    INNER JOIN persons ON families.person_id = persons.id
    LEFT JOIN wishes ON customers.id = wishes.customer_id;
END;