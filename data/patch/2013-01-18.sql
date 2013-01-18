BEGIN;
    ALTER TABLE settlement DROP COLUMN currency_rate;
COMMIT;