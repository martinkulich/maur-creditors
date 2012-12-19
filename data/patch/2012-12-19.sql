BEGIN;
    ALTER TABLE contract ADD COLUMN capitalize boolean NOT NULL DEFAULT false;
COMMIT;