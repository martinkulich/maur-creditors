BEGIN;
  ALTER TABLE outgoing_payment ADD COLUMN refundation numeric(15,2);
COMMIT;
