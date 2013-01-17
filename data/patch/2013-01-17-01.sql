BEGIN;
  ALTER TABLE allocation DROP CONSTRAINT allocation_outgoing_payment_id_fkey;

ALTER TABLE allocation
  ADD CONSTRAINT allocation_outgoing_payment_id_fkey FOREIGN KEY (outgoing_payment_id)
      REFERENCES outgoing_payment (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
COMMIT;