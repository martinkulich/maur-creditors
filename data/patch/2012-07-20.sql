BEGIN;

ALTER TABLE settlement ADD COLUMN manual_interest boolean NOT NULL DEFAULT false;
ALTER TABLE settlement ADD COLUMN manual_balance boolean NOT NULL DEFAULT false;

ALTER TABLE settlement ADD COLUMN date_of_payment date;

ALTER TABLE settlement ALTER bank_account TYPE character varying;

ALTER TABLE payment ADD COLUMN payment_type CHARACTER VARYING DEFAULT 'payment' NOT NULL;


COMMIT;