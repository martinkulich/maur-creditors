BEGIN;
ALTER TABLE settlement DROP COLUMN calculate_first_date;

COMMIT;