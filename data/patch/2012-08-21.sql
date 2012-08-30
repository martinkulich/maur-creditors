BEGIN;

ALTER TABLE creditor ADD COLUMN birth_date timestamp without time zone;


COMMIT;