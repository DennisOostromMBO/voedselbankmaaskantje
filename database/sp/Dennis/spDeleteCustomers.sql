DROP PROCEDURE IF EXISTS spDeleteCustomer;
CREATE PROCEDURE spDeleteCustomer(IN customerId INT)
    DELETE FROM customers WHERE id = customerId;
