BEGIN;
  ALTER TABLE allocation DROP CONSTRAINT allocation_outgoing_payment_id_fkey;

ALTER TABLE allocation
  ADD CONSTRAINT allocation_outgoing_payment_id_fkey FOREIGN KEY (outgoing_payment_id)
      REFERENCES outgoing_payment (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

UPDATE outgoing_payment
SET cash = true,
bank_account_id = null
WHERE id IN (
  SELECT a.outgoing_payment_id FROM settlement s
  JOIN allocation a
    ON a.settlement_id = s.id
  WHERE cash = true
);

ALTER TABLE settlement DROP COLUMN cash;
COMMIT;