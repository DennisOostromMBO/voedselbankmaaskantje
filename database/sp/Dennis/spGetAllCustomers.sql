DROP PROCEDURE IF EXISTS spGetAllCustomers;
CREATE PROCEDURE spGetAllCustomers()
BEGIN
    SELECT
        customers.id,
        persons.first_name,
        persons.infix,
        persons.last_name,
        persons.full_name,
        families.name AS family_name,
        contacts.full_address,
        contacts.street,
        contacts.house_number,
        contacts.addition,
        contacts.postcode,
        contacts.city,
        contacts.mobile,
        contacts.email,
        persons.age,
        customers.number AS customer_number,
        wishes.choices AS wish
    FROM contacts
    INNER JOIN customers ON contacts.customer_id = customers.id
    INNER JOIN families ON customers.family_id = families.id
    INNER JOIN persons ON families.person_id = persons.id
    LEFT JOIN wishes ON customers.id = wishes.customer_id
    ORDER BY customers.id DESC;
END;