BEGIN;

ALTER TABLE price_amount ALTER amount TYPE numeric(9,2);


COMMIT;