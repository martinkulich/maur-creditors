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

ALTER TABLE outgoing_payment ADD COLUMN receiver_bank_account character varying;

update outgoing_payment op set receiver_bank_account = (select distinct s.bank_account from settlement s
join allocation a on a.settlement_id = s.id
Join outgoing_payment op1 ON op1.id = a.outgoing_payment_id
where op.id =op1.id
and s.bank_account != ''
);

ALTER TABLE settlement drop column bank_account;

COMMIT;