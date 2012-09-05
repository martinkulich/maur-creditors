BEGIN;
    ALTER TABLE settlement ADD COLUMN calculate_first_date boolean DEFAULT false;


COMMIT;