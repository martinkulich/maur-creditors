BEGIN;
ALTER TABLE contract  ADD COLUMN first_settlement_date date;


COMMIT;