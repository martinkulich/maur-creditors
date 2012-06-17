BEGIN;

    ALTER TABLE payment ADD COLUMN cash BOOLEAN NOT NULL DEFAULT false;
    ALTER TABLE payment ADD COLUMN bank_account character varying;


COMMIT;