BEGIN;
    ALTER TABLE outgoing_payment ADD COLUMN cash boolean;
COMMIT;