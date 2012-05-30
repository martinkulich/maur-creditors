BEGIN;

ALTER TABLE creditor ADD COLUMN note text;
ALTER TABLE contract ADD COLUMN note text;
ALTER TABLE payment ADD COLUMN note text;
ALTER TABLE settlement ADD COLUMN note text;
ALTER TABLE settlement ADD COLUMN bank_account text;
ALTER TABLE settlement ADD COLUMN cash  boolean;


CREATE TABLE currency
(
   code character(3) NOT NULL,
    PRIMARY KEY (code)
)
WITH (OIDS = FALSE);

ALTER TABLE contract ADD COLUMN currency_code character(3);

INSERT INTO currency(code) VALUES('CZK');
INSERT INTO currency(code) VALUES('EUR');

UPDATE contract SET currency_code = 'CZK';
ALTER TABLE contract ALTER COLUMN currency_code SET NOT NULL;

ALTER TABLE contract ADD CONSTRAINT contract_currency_code_fkey FOREIGN KEY (currency_code) REFERENCES currency (code) ON UPDATE CASCADE ON DELETE RESTRICT;



COMMIT;