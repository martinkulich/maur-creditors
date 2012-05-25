BEGIN;

ALTER TABLE reservation ADD COLUMN paid boolean;

COMMIT;