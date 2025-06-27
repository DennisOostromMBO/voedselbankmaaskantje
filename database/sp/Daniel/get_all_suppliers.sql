CREATE PROCEDURE get_all_suppliers()
BEGIN
    SELECT
        s.id,
        s.supplier_name,
        s.contact_number,
        s.is_active,
        s.note,
        s.upcoming_delivery_at,
        s.created_at,
        s.updated_at,
        c.street,
        c.postcode,
        c.house_number,
        c.addition,
        c.city,
        c.mobile,
        c.email,
        c.full_address,
        c.is_active AS contact_is_active,
        c.note AS contact_note
    FROM suppliers s
    LEFT JOIN contacts c ON c.supplier_id = s.id;
END;
