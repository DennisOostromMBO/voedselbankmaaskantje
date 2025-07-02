CREATE PROCEDURE sp_get_food_parcel_stats()
BEGIN
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
        SUM(CASE
            WHEN YEAR(created_at) = YEAR(NOW())
            AND MONTH(created_at) = MONTH(NOW())
            THEN 1
            ELSE 0
        END) as this_month
    FROM food_parcels;
END;
